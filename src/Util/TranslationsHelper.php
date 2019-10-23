<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/08/2019
 * Time: 12:41
 */

namespace App\Util;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TranslationsHelper
 * @package App\Util
 */
class TranslationsHelper
{
    /**
     * @var TranslatorInterface
     */
    private static $translator;

    /**
     * @var array
     */
    private static $translations;

    /**
     * @var string
     */
    private static $domain = 'messages';

    /**
     * TranslationsHelper constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
       self::$translator = $translator;
    }

    /**
     * getTranslations
     * @return array
     */
    public static function getTranslations(): array
    {
        return self::$translations = self::$translations ?: [];
    }

    /**
     * setTranslations
     * @param array $translations
     */
    public static function setTranslations(array $translations)
    {
        self::$translations = $translations;
    }

    /**
     * addTranslation
     * @return ReactFormType
     */
    public static function addTranslation(string $id, array $options = [], ?string $domain = null)
    {
        self::getTranslations();
        self::$translations[$id] = self::translate($id, $options, $domain ?: self::getDomain());
    }

    /**
     * translate
     * @param string $id
     * @param array $params
     * @param string|null $domain  Override the default messages.
     * @return string
     */
    public static function translate(string $id, array $params = [], ?string $domain = null): string
    {
        if (null === self::$translator)
            return $id;
        return self::$translator->trans($id, $params, $domain ?: self::getDomain());
    }

    /**
     * @return string
     */
    public static function getDomain(): string
    {
        return self::$domain ?: 'messages';
    }

    /**
     * @param string $domain
     */
    public static function setDomain(string $domain): void
    {
        self::$domain = $domain;
    }
}