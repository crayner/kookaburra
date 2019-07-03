<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 10:27
 */

namespace App\Provider;

use App\Entity\I18n;
use App\Entity\Person;
use App\Entity\Setting;
use App\Manager\Traits\EntityTrait;
use Gibbon\Contracts\Services\Session;
use Gibbon\Services\Format;
use PDOException;

class SettingProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Setting::class;

    /**
     * getSettingByScope
     * @param string $scope
     * @param string $name
     * @param bool $returnRow
     * @return \App\Manager\EntityInterface|bool|null
     * @throws \Exception
     */
    function getSettingByScope(string $scope, string $name, $returnRow = false)
    {
        $setting = $this->findOneBy(['scope' => $scope, 'name' => $name]);

        if (null === $setting) {
            return false;
        }
        if ($returnRow) {
            return $setting;
        }

        return $setting->getValue();
    }

    /**
     * getSystemSettings
     * @throws \Exception
     */
    function getSystemSettings(Session $session)
    {
        $session->set('systemSettingsSet', false);
        //System settings from gibbonSetting
        $result = $this->findBy(['scope' => 'System']);

        foreach($result as $setting)
        {
            $session->set($setting->getName(), $setting->getValue());
        }

        //Get names and emails for administrator, dba, admissions
        //System Administrator
        $result = $this->getRepository(Person::class)->findOneBy(['id' => $session->get('organisationAdministrator')]);
        $session->set('organisationAdministratorName', Format::name('', $result->getPreferredName(), $result->getSurname(), 'Staff', false, true));
        $session->set('organisationAdministratorEmail', $result->getEmail());

        //DBA
        $result = $this->getRepository(Person::class)->findOneBy(['id' => $session->get('organisationDBA')]);
        $session->set('organisationDBAName', Format::name('', $result->getPreferredName(), $result->getSurname(), 'Staff', false, true));
        $session->set('organisationDBAEmail', $result->getEmail());

        //Admissions
        $result = $this->getRepository(Person::class)->findOneBy(['id' => $session->get('organisationAdmissions')]);
        $session->set('organisationAdmissionsName', Format::name('', $result->getPreferredName(), $result->getSurname(), 'Staff', false, true));
        $session->set('organisationAdmissionsEmail', $result->getEmail());

        //HR Administraotr
        $result = $this->getRepository(Person::class)->findOneBy(['id' => $session->get('organisationHR')]);
        $session->set('organisationHRName', Format::name('', $result->getPreferredName(), $result->getSurname(), 'Staff', false, true));
        $session->set('organisationHREmail', $result->getEmail());

        //Language settings from gibboni18n
        $result = $this->getProviderFactory()->getProvider(I18n::class)->setLanguageSession($session);

        $session->set('systemSettingsSet',true);
    }
}