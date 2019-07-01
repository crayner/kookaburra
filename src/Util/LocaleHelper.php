<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * UserProvider: craig
 * Date: 24/11/2018
 * Time: 14:00
 */
namespace App\Util;

use App\Entity\I18n;
use App\Entity\Person;
use App\Provider\I18nProvider;
use App\Provider\ProviderFactory;

class LocaleHelper
{
    /**
     * @var string
     */
    private static $locale = 'en';

    /**
     * @var I18nProvider
     */
    private static $provider;

    /**
     * LocaleHelper constructor.
     * @param string $locale
     */
    public function __construct(ProviderFactory $providerFactory, string $locale = 'en')
    {
        self::$locale = self::getDefaultLocale($locale);
        self::$provider = $providerFactory->getProvider(I18n::class);
        $user = UserHelper::getCurrentUser();
        if ($user instanceof Person)
            self::$locale = ! empty($user->getI18nPersonal()) && ! empty($user->getI18nPersonal()->getCode()) ? $user->getI18nPersonal()->getCode() : self::$locale ;
    }

    /**
     * getLocale
     *
     * @return string
     */
    public static function getLocale(): string
    {
        return self::$locale;
    }

    /**
     * getDefaultLocale
     * @param string $locale
     * @return string
     */
    public static function getDefaultLocale(string $locale): string
    {
        if ($locale !== 'en' || empty(self::$provider))
            return $locale;
        return self::$provider->getRepository()->createQueryBuilder('i')
            ->where('i.systemDefault = :yes')
            ->setParameter('yes', 'Y')
            ->select('i.code')
            ->getQuery()
            ->getSingleScalarResult() ?: $locale;
    }
}