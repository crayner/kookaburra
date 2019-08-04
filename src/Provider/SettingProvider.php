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
use App\Exception\SettingNotFoundException;
use App\Manager\Traits\EntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @var ArrayCollection
     */
    private $settings;

    /**
     * getSettingByScope
     * @param string $scope
     * @param string $name
     * @param bool $returnRow
     * @return \App\Manager\EntityInterface|bool|null
     * @throws \Exception
     */
    public function getSettingByScope(string $scope, string $name, $returnRow = false)
    {
        $setting = $this->getSetting($scope, $name) ?: $this->findOneBy(['scope' => $scope, 'name' => $name]);

        if (null === $setting) {
            return false;
        }

        $this->addSetting($setting);

        if ($returnRow) {
            return $setting;
        }


        return $setting->getValue();
    }

    /**
     * getSystemSettings
     * @throws \Exception
     */
    public function getSystemSettings(Session $session)
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

    /**
     * getSettingByScopeAsInteger
     * @param string $scope
     * @param string $name
     * @param int $default
     * @return int
     * @throws \Exception
     */
    public function getSettingByScopeAsInteger(string $scope, string $name, int $default = 0): int
    {
        $result = $this->getSettingByScope($scope, $name);
        if (empty($result))
            return $default;
        return intval($result);
    }

    /**
     * getSettingByScopeAsArray
     * @param string $scope
     * @param string $name
     * @param array $default
     * @return array
     * @throws \Exception
     */
    public function getSettingByScopeAsArray(string $scope, string$name, array $default = []): array
    {
        $result = $this->getSettingByScope($scope, $name);
        if (empty($result))
            return $default;
        return explode(',', $result);
    }

    /**
     * getSettingByScopeAsArray
     * @param string $scope
     * @param string $name
     * @param array $default
     * @return array
     * @throws \Exception
     */
    public function getSettingByScopeAsDate(string $scope, string $name, ?\DateTime $default = null)
    {
        $result = $this->getSettingByScope($scope, $name);
        if (empty($result))
            return $default;
        return unserialize($result);
    }

    /**
     * getSettingByScopeAsBoolean
     * @param string $scope
     * @param string $name
     * @param bool|null $default
     * @return bool|null
     * @throws \Exception
     */
    public function getSettingByScopeAsBoolean(string $scope, string $name, ?bool $default = false)
    {
        $result = $this->getSettingByScope($scope, $name);
        if (empty($result))
            return $default;
        return $result === 'Y' ? true : false ;
    }

    /**
     * getSettingByScopeAsString
     * @param string $scope
     * @param string $name
     * @param string|null $default
     * @return string|null
     * @throws \Exception
     */
    public function getSettingByScopeAsString(string $scope, string $name, ?string $default = null)
    {
        $result = $this->getSettingByScope($scope, $name);
        if (empty($result))
            return $default;
        return strval($result);
    }

    /**
     * setSettingByScope
     * @param string $scope
     * @param string $name
     * @param string $value
     * @throws SettingNotFoundException
     */
    public function setSettingByScope(string $scope, string $name, string $value)
    {
        $setting = $this->getSettingByScope($scope, $name, true);
        if (false === $setting)
            throw new SettingNotFoundException($scope,$name);

        $setting->setValue($value);
        $this->saveEntity();
    }

    /**
     * @return ArrayCollection
     */
    public function getSettings(): ArrayCollection
    {
        if (null === $this->settings)
            $this->settings = new ArrayCollection();

        return $this->settings;
    }

    /**
     * Settings.
     *
     * @param ArrayCollection $settings
     * @return SettingProvider
     */
    public function setSettings(ArrayCollection $settings): SettingProvider
    {
        $this->settings = $settings;
        return $this;
    }

    /**
     * addSetting
     * @param Setting $setting
     * @return SettingProvider
     */
    private function addSetting(Setting $setting): SettingProvider
    {
        $scope = $setting->getScope();
        $name = $setting->getName();
        if (!$this->getSettings()->containsKey($scope))
            $this->settings->set($scope, new ArrayCollection());

        $this->settings->get($scope)->set($name, $setting);
        return $this;
    }

    /**
     * getSetting
     * @param $scope
     * @param $name
     * @return |null
     */
    private function getSetting($scope, $name)
    {
        if (!$this->getSettings()->containsKey($scope))
            $this->settings->set($scope, new ArrayCollection());

        return $this->settings->get($scope)->containskey($name) ? $this->settings->get($scope)->get($name) : null;
    }
}