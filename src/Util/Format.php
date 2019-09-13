<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 13:58
 */

namespace App\Util;

use DateTime;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Format
{
    /**
     * @var array
     */
    protected static $settings = [
        'dateFormatPHP'     => 'd/m/Y',
        'dateTimeFormatPHP' => 'd/m/Y H:i',
        'timeFormatPHP'     => 'H:i',
    ];

    /**
     * getSetting
     * @param string $name
     * @return mixed
     */
    public static function getSetting(string $name)
    {
        return static::$settings[$name];
    }

    /**
     * Sets the formatting options from session i18n and database settings.
     *
     * @param Session $session
     */
    public static function setupFromSession(SessionInterface $session)
    {
        $settings = $session->get('i18n');

        $settings['absolutePath'] = $session->get('absolutePath');
        $settings['absoluteURL'] = $session->get('absoluteURL');
        $settings['gibbonThemeName'] = $session->get('gibbonThemeName');
        $settings['currency'] = $session->get('currency');
        $settings['currencySymbol'] = !empty(substr($settings['currency'], 4)) ? substr($settings['currency'], 4) : '';
        $settings['currencyName'] = substr($settings['currency'], 0, 3);
        $settings['nameFormatStaffInformal'] = $session->get('nameFormatStaffInformal');
        $settings['nameFormatStaffInformalReversed'] = $session->get('nameFormatStaffInformalReversed');
        $settings['nameFormatStaffFormal'] = $session->get('nameFormatStaffFormal');
        $settings['nameFormatStaffFormalReversed'] = $session->get('nameFormatStaffFormalReversed');

        static::setup($settings);
    }

    /**
     * Sets the internal formatting options from an array.
     *
     * @param array $settings
     */
    public static function setup(array $settings)
    {
        static::$settings = array_replace(static::$settings, $settings);
    }

    /**
     * Formats a list of names from an array containing standard title, preferredName & surname fields.
     *
     * @param array $list
     * @param string $roleCategory
     * @param bool $reverse
     * @param bool $informal
     * @return string
     */
    public static function nameList(array $list, string $roleCategory = 'Staff', bool $reverse = false, bool $informal = false, ?string $separator = '<br/>')
    {
        $listFormatted = array_map(function ($person) use ($roleCategory, $reverse, $informal) {
            return static::name($person->getTitle(), $person->getPreferredName(), $person->getSurname(), $roleCategory, $reverse, $informal);
        }, $list);

        if (null === $separator)
            return $listFormatted;
        return implode($separator, $listFormatted);
    }

    /**
     * Formats a name based on the provided Role Category. Optionally reverses the name (surname first) or uses an informal format (no title).
     *
     * @param string $title
     * @param string $preferredName
     * @param string $surname
     * @param string $roleCategory
     * @param bool $reverse
     * @param bool $informal
     * @return string
     */
    public static function name($title, $preferredName, $surname, $roleCategory = 'Staff', $reverse = false, $informal = false)
    {
        $output = '';

        if (empty($preferredName) && empty($surname)) return '';

        if ($roleCategory == 'Staff' or $roleCategory == 'Other') {
            $setting = 'nameFormatStaff' . ($informal ? 'Informal' : 'Formal') . ($reverse? 'Reversed' : '');
            $format = isset(static::$settings[$setting])? static::$settings[$setting] : '[title] [preferredName]. [surname]';

            $output = preg_replace_callback('/\[+([^\]]*)\]+/u',
                function ($matches) use ($title, $preferredName, $surname) {
                    list($token, $length) = array_pad(explode(':', $matches[1], 2), 2, false);
                    return isset($$token)
                        ? (!empty($length)? mb_substr($$token, 0, intval($length)) : $$token)
                        : '';
                },
                $format);

        } elseif ($roleCategory == 'Parent') {
            $format = (!$informal? '{oneString} ' : '') . ($reverse? '{threeString}, {twoString}' : '{twoString} {threeString}');
            $output = sprintf($format, $title, $preferredName, $surname);
        } elseif ($roleCategory == 'Student') {
            $format = $reverse ? '{twoString}, {oneString}' : '{oneString} {twoString}';
            $output = sprintf($format, $preferredName, $surname);
        }

        return trim($output, ' ');
    }

    /**
     * Formats a YYYY-MM-DD date with the language-specific format. Optionally provide a format string to use instead.
     *
     * @param DateTime|string $dateString
     * @param string $format
     * @return string
     */
    public static function date($dateString, $format = false)
    {
        if (empty($dateString)) return '';
        $date = static::createDateTime($dateString);
        return $date ? $date->format($format ? $format : static::$settings['dateFormatPHP']) : $dateString;
    }

    /**
     * createDateTime
     * @param $dateOriginal
     * @param null $expectedFormat
     * @param null $timezone
     * @return DateTimeImmutable
     * @throws \Exception
     */
    private static function createDateTime($dateOriginal, $expectedFormat = null, $timezone = null): DateTime
    {
        if ($dateOriginal instanceof DateTime || $dateOriginal instanceof DateTimeImmutable) return $dateOriginal;

        return !empty($expectedFormat)
            ? DateTime::createFromFormat($expectedFormat, $dateOriginal, $timezone)
            : new DateTime($dateOriginal, $timezone);
    }

    /**
     * Converts a date in the language-specific format to YYYY-MM-DD.
     *
     * @param DateTime|string $dateString
     * @return string
     */
    public static function dateConvert($dateString)
    {
        if (empty($dateString)) return '';
        $date = static::createDateTime($dateString, static::$settings['dateFormatPHP']);
        return $date ? $date->format('Y-m-d') : $dateString;
    }

    /**
     * Converts a YYYY-MM-DD date to a Unix timestamp.
     *
     * @param DateTime|string $dateString
     * @param string $timezone
     * @return int
     */
    public static function timestamp($dateString, $timezone = null)
    {
        if (strlen($dateString) == 10) $dateString .= ' 00:00:00';
        $date = static::createDateTime($dateString, 'Y-m-d H:i:s', $timezone);
        return $date ? $date->getTimestamp() : 0;
    }
}