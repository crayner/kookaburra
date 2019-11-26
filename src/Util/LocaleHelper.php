<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * UserProvider: craig
 * Date: 24/11/2018
 * Time: 14:00
 */
namespace App\Util;

use App\Entity\I18n;
use Kookaburra\UserAdmin\Entity\Person;
use App\Provider\I18nProvider;
use App\Provider\ProviderFactory;
use Doctrine\DBAL\Exception\ConnectionException;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Kookaburra\UserAdmin\Util\UserHelper;
use Symfony\Component\Intl\Countries;

class LocaleHelper
{
    /**
     * @var string
     */
    private static $locale;

    /**
     * @var I18nProvider
     */
    private static $provider;

    /**
     * LocaleHelper constructor.
     * @param string $locale
     */
    public function __construct(ProviderFactory $providerFactory, string $locale = null)
    {
        self::$locale = $locale;
        self::$provider = $providerFactory->getProvider(I18n::class);
        self::getLocale();
    }

    /**
     * getCurrentLocale
     * @return string|null
     * @throws \Exception
     */
    private static function getCurrentLocale()
    {
        $user = UserHelper::getCurrentUser();
        if ($user instanceof Person)
            self::$locale = ! empty($user->getI18nPersonal()) && ! empty($user->getI18nPersonal()->getCode()) ? $user->getI18nPersonal()->getCode() : self::$locale;

        if (null === self::$locale)
            self::getDefaultLocale('en_GB');
        if (null === self::$locale)
            self::$locale = 'en_GB';
        return self::$locale;
    }

    /**
     * getLocale
     * @param bool $refresh
     * @return string
     * @throws \Exception
     */
    public static function getLocale(bool $refresh = false): string
    {
        if (null === self::$locale || $refresh)
            self::$locale = self::getCurrentLocale();
        return self::$locale;
    }

    /**
     * getDefaultLocale
     * @param string $locale
     * @return string
     */
    public static function getDefaultLocale(string $locale): string
    {
        if ($locale !== 'en_GB' || empty(self::$provider))
            return $locale;
        try {
            return self::$provider->getRepository()->findSystemDefaultCode() ?: $locale;
        } catch (ConnectionException $e) {
            return $locale;
        } catch (\ErrorException $e) {
            return $locale;
        } catch (TableNotFoundException $e) {
            return $locale;
        }
    }

    /**
     * getCountryName
     * @param string $code
     * @return string
     */
    public static function getCountryName(string $code): string
    {
        return Countries::getName($code);
    }
}