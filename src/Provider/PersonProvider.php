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
use App\Entity\INPersonDescriptor;
use App\Entity\MarkbookEntry;
use App\Entity\Person;
use App\Entity\Setting;
use App\Manager\Traits\EntityTrait;
use App\Security\SecurityUser;
use App\Util\SecurityHelper;
use Gibbon\Services\Format;
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
                        'highestColour'   => $alert->getColor(),
                        'highestColourBG' => $alert->getcolorBG(),
                        'tag'             => 'A',
                        'title'           => 'concerns_alert_level', // 'Student has a %name% alert for academic concern over the past 60 days.',
                        'title_params'    => array_merge(['name' => $alert->getName(), 'highest_level' => $alert->getName()],  $alertThresholdParams),
                        'translation_domain'    => 'kookaburra',
                        'link'            => './?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person->getId().'&subpage=Markbook&filter='.$this->getSession()->get('schoolYear')->getId(),
                    ]);
                }
            }
dd($alerts);
            // Behaviour
            $alertLevelID = '';
            $alertThresholdText = '';
            try {
                $dataAlert = array('gibbonPersonID' => $person, 'date' => date('Y-m-d', (time() - (24 * 60 * 60 * 60))));
                $sqlAlert = "SELECT * FROM gibbonBehaviour WHERE gibbonPersonID=:gibbonPersonID AND type='Negative' AND date>:date";
                $resultAlert = $connection2->prepare($sqlAlert);
                $resultAlert->execute($dataAlert);
            } catch (PDOException $e) {}

            $behaviourAlertLowThreshold = getSettingByScope($connection2, 'Students', 'behaviourAlertLowThreshold');
            $behaviourAlertMediumThreshold = getSettingByScope($connection2, 'Students', 'behaviourAlertMediumThreshold');
            $behaviourAlertHighThreshold = getSettingByScope($connection2, 'Students', 'behaviourAlertHighThreshold');

            if ($resultAlert->rowCount() >= $behaviourAlertHighThreshold) {
                $alertLevelID = 001;
                $alertThresholdText = sprintf(__('This alert level occurs when there are more than %1$s events recorded for a student.'), $behaviourAlertHighThreshold);
            } elseif ($resultAlert->rowCount() >= $behaviourAlertMediumThreshold) {
                $alertLevelID = 002;
                $alertThresholdText = sprintf(__('This alert level occurs when there are between %1$s and %2$s events recorded for a student.'), $behaviourAlertMediumThreshold, ($behaviourAlertHighThreshold-1));
            } elseif ($resultAlert->rowCount() >= $behaviourAlertLowThreshold) {
                $alertLevelID = 003;
                $alertThresholdText = sprintf(__('This alert level occurs when there are between %1$s and %2$s events recorded for a student.'), $behaviourAlertLowThreshold, ($behaviourAlertMediumThreshold-1));
            }

            if ($alertLevelID != '') {
                if ($alert = getAlert($guid, $connection2, $alertLevelID)) {
                    $alerts[] = [
                        'highestLevel'    => __($alert['name']),
                        'highestColour'   => $alert['color'],
                        'highestColourBG' => $alert['colorBG'],
                        'tag'             => __('B'),
                        'title'           => sprintf(__('Student has a %1$s alert for behaviour over the past 60 days.'), __($alert['name'])).' '.$alertThresholdText,
                        'link'            => './index.php?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person.'&subpage=Behaviour',
                    ];
                }
            }

            // Medical
            if ($alert = getHighestMedicalRisk($guid, $person, $connection2)) {
                $alerts[] = [
                    'highestLevel'    => $alert[1],
                    'highestColour'   => $alert[3],
                    'highestColourBG' => $alert[4],
                    'tag'             => __('M'),
                    'title'           => sprintf(__('Medical alerts are set, up to a maximum of %1$s'), $alert[1]),
                    'link'            => './index.php?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person.'&subpage=Medical',
                ];
            }

            // Privacy
            $privacySetting = getSettingByScope($connection2, 'User Admin', 'privacy');
            if ($privacySetting == 'Y' and $privacy != '') {
                if ($alert = getAlert($guid, $connection2, 001)) {
                    $alerts[] = [
                        'highestLevel'    => __($alert['name']),
                        'highestColour'   => $alert['color'],
                        'highestColourBG' => $alert['colorBG'],
                        'tag'             => __('P'),
                        'title'           => sprintf(__('Privacy is required: %1$s'), $privacy),
                        'link'            => './index.php?q=/modules/Students/student_view_details.php&gibbonPersonID='.$person,
                    ];
                }
            }

            // Output alerts
            $classDefault = 'block align-middle text-center font-bold border-0 border-t-2 ';
            $classDefault .= $large
                ? 'text-4xl w-10 pt-1 mr-2 leading-none'
                : 'text-xs w-4 pt-px mr-1 leading-none';

            foreach ($alerts as $alert) {
                $style = "color: #{$alert['highestColour']}; border-color: #{$alert['highestColour']}; background-color: #{$alert['highestColourBG']};";
                $class = $classDefault .' '. ($alert['class'] ?? 'float-left');
                $output .= Format::link($alert['link'], $alert['tag'], [
                    'title' => $alert['title'],
                    'class' => $class,
                    'style' => $style,
                ]);
            }

            if ($div == true) {
                $output = "<div {$divExtras} class='w-20 lg:w-24 h-6 text-left py-1 px-0 mx-auto'>{$output}</div>";
            }
        }

        return $output;
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