<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 09:47
 */

namespace App\Provider;

use App\Entity\AlertLevel;
use App\Entity\Behaviour;
use App\Entity\INPersonDescriptor;
use App\Entity\MarkbookEntry;
use App\Entity\Person;
use App\Entity\PersonMedical;
use App\Entity\Setting;
use App\Manager\Traits\EntityTrait;
use App\Security\SecurityUser;
use App\Util\SecurityHelper;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;

class PersonProvider implements EntityProviderInterface, UserLoaderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Person::class;

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        $person = $this->getRepository()->loadUserByUsernameOrEmail($username);

        return $person ? new SecurityUser($person) : null;
    }

    /**
     * getAlertBar
     * @param Person $person
     * @param string $divExtras
     * @param bool $div
     * @param bool $large
     * @return string
     */
    function getAlertBar(Person $person, string $divExtras = '', bool $div = true, bool $large = false)
    {
        $output = '';
        $alerts = [];
        $privacy = $person->getPrivacy();
        $highestAction = SecurityHelper::getHighestGroupedAction('/modules/Students/student_view_details.php');
        if ($highestAction == 'View Student Profile_full' || $highestAction == 'View Student Profile_fullNoNotes') {

            // Individual Needs
            $in_alerts = $this->getRepository(INPersonDescriptor::class)->findAlertsByPerson($person) ?: [];

            if (count($in_alerts) > 0) {
                $alert = reset($in_alerts);
                $alerts[] = $this->resolveAlert(
                    [
                        'highestLevel'    => $alert['name'],
                        'highestColour'   => $alert['color'],
                        'highestColourBG' => $alert['colorBG'],
                        'tag'             => 'IN',
                        'title'           => 'in_alert_level',
                        'title_params'    => ['%count%' => count($in_alerts), '%name%' => $in_alerts[0]->getName()],
                        'link'            => './?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person->getId().'&subpage=Individual Needs',
                    ]
                );
            }

            // Academic
            $alertLevelID = 0;
            $alertThresholdText = '';

            $results = $this->getRepository(MarkbookEntry::class)->findAttainmentOrEffortConcerns($person, $this->getSession()->get('schoolYear'));

            $settingProvider = ProviderFactory::create(Setting::class);
            $academicAlertLowThreshold = $settingProvider->getSettingByScope('Students', 'academicAlertLowThreshold');
            $academicAlertMediumThreshold = $settingProvider->getSettingByScope('Students', 'academicAlertMediumThreshold');
            $academicAlertHighThreshold = $settingProvider->getSettingByScope('Students', 'academicAlertHighThreshold');

            if (count($results) >= $academicAlertHighThreshold) {
                $alertLevelID = 001;
                $alertThresholdParams = ['low' => $academicAlertHighThreshold];
            } elseif (count($results) >= $academicAlertMediumThreshold) {
                $alertLevelID = 002;
                $alertThresholdParams = ['high' => $academicAlertHighThreshold - 1, 'low' => $academicAlertMediumThreshold];
            } elseif (count($results) >= $academicAlertLowThreshold) {
                $alertLevelID = 003;
                $alertThresholdParams = ['high' => $academicAlertMediumThreshold - 1, 'low' => $academicAlertLowThreshold];
            }
            if ($alertLevelID != '') {
                if ($alert = $this->providerFactory::getRepository(AlertLevel::class)->find($alertLevelID)) {
                    $alerts[] = $this->resolveAlert([
                        'highestLevel'    => $alert->getName(),
                        'highestColour'   => $alert->getColour(),
                        'highestColourBG' => $alert->getColourBG(),
                        'tag'             => 'A',
                        'title'           => 'concerns_alert_level', // 'Student has a %name% alert for academic concern over the past 60 days.',
                        'title_params'    => array_merge(['name' => $alert->getName(), 'highest_level' => $alert->getName()],  $alertThresholdParams),
                        'translation_domain'    => 'kookaburra',
                        'link'            => './?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person->getId().'&subpage=Markbook&filter='.$this->getSession()->get('schoolYear')->getId(),
                    ]);
                }
            }

            // Behaviour
            $alertLevelID = '';
            $alertThresholdText = '';

            $results = ProviderFactory::getRepository(Behaviour::class)->findNegativeInLast60Days($person);

            $behaviourAlertLowThreshold = $settingProvider->getSettingByScope('Students', 'behaviourAlertLowThreshold');
            $behaviourAlertMediumThreshold = $settingProvider->getSettingByScope('Students', 'behaviourAlertMediumThreshold');
            $behaviourAlertHighThreshold = $settingProvider->getSettingByScope('Students', 'behaviourAlertHighThreshold');

            if (count($results) >= $behaviourAlertHighThreshold) {
                $alertLevelID = 001;
                $alertThresholdParams = ['low' => $behaviourAlertHighThreshold];
            } elseif (count($results) >= $behaviourAlertMediumThreshold) {
                $alertLevelID = 002;
                $alertThresholdParams = ['high' => $behaviourAlertHighThreshold - 1, 'low' => $behaviourAlertMediumThreshold];
            } elseif (count($results) >= $behaviourAlertLowThreshold) {
                $alertLevelID = 003;
                $alertThresholdParams = ['high' => $behaviourAlertMediumThreshold - 1, 'low' => $behaviourAlertLowThreshold];
            }

            if ($alertLevelID != '') {
                if ($alert = $this->providerFactory::getRepository(AlertLevel::class)->find($alertLevelID)) {
                    $alerts[] = $this->resolveAlert([
                        'highestLevel'    => $alert->getName(),
                        'highestColour'   => $alert->getColour(),
                        'highestColourBG' => $alert->getColourBG(),
                        'tag'             => 'B',
                        'title'           => 'behaviour_alert_level', // 'Student has a %name% alert for academic concern over the past 60 days.',
                        'title_params'    => array_merge(['name' => $alert->getName(), 'highest_level' => $alert->getName()],  $alertThresholdParams),
                        'link'            => './?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person->getId().'&subpage=Behaviour',
                        'translation_domain' => 'kookaburra',
                    ]);
                }
            }

            // Medical
            if ($alert = ProviderFactory::getRepository(PersonMedical::class)->findHighestMedicalRisk($person)) {
                dd($alert);
                $alerts[] = $this->resolveAlert([
                    'highestLevel'    => $alert[1],
                    'highestColour'   => $alert[3],
                    'highestColourBG' => $alert[4],
                    'tag'             => 'M',
                    'title'           => 'medical_alert_level',
                    'title_params'    => ['name' => $alert->getName()],
                    'translation_domain' => 'kookaburra',
                    'link'            => './?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person->getId().'&subpage=Medical',
                ]);
            }

            // Privacy
            $privacySetting = $settingProvider->getSettingByScopeAsBoolean('User Admin', 'privacy');
            if ($privacySetting && $privacy !== '' && null !== $privacy) {
                if ($alert = ProviderFactory::getRepository(AlertLevel::class)->find(1)) {
                    $alerts[] = $this->resolveAlert([
                        'highestLevel'    => $alert->getName(),
                        'highestColour'   => $alert->getColour(),
                        'highestColourBG' => $alert->getColourBG(),
                        'tag'             => 'P',
                        'title'           => 'privacy_alert_level', // sprintf(__('Privacy is required: %1$s'), $privacy),
                        'title_params'    => ['message' => $privacy],
                        'translation_domain' => 'kookaburra',
                        'link'            => './?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person->getId(),
                    ]);
                }
            }

            // Output alerts

            $alerts['alerts'] = $alerts;
            $alerts['classDefault'] = 'block align-middle text-center font-bold border-0 border-t-2 ';
            $alerts['classDefault'] .= $large
                ? 'text-4xl w-10 pt-1 mr-2 leading-none'
                : 'text-xs w-4 pt-px mr-1 leading-none';

            if ($div) {
                $alerts['wrapperClass'] =  'w-20 lg:w-24 h-6 text-left py-1 px-0 mx-auto';
                $alerts['wrapper'] = true;
                $alerts['wrapperExtras'] = $divExtras;
            }
        }

        return $alerts;
    }

    /**
     * resolveAlert
     * @param array $alert
     * @return array
     */
    private function resolveAlert(array $alert)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(
            [
                'highestLevel',
                'highestColour',
                'highestColourBG',
                'tag',
                'title',
                'link',
            ]
        );
        $resolver->setDefaults(
            [
                'title_params' => [],
                'translation_domain' => 'gibbon',
            ]
        );
        $resolver->addAllowedValues('highestLevel', ['High', 'Medium', 'Low']);
        $resolver->setAllowedTypes('tag', ['string']);
        $resolver->setAllowedTypes('highestColour', ['string']);
        $resolver->setAllowedTypes('highestColourBG', ['string']);
        $resolver->setAllowedTypes('title', ['string']);
        $resolver->setAllowedTypes('link', ['string']);
        return $resolver->resolve($alert);
    }
}