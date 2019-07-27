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
            $setting = 'nameFormatStaff' . ($informal? 'Informal' : 'Formal') . ($reverse? 'Reversed' : '');
            $format = isset(static::$settings[$setting])? static::$settings[$setting] : '[title] [preferredName:1]. [surname]';

            $output = preg_replace_callback('/\[+([^\]]*)\]+/u',
                function ($matches) use ($title, $preferredName, $surname) {
                    list($token, $length) = array_pad(explode(':', $matches[1], 2), 2, false);
                    return isset($$token)
                        ? (!empty($length)? mb_substr($$token, 0, intval($length)) : $$token)
                        : '';
                },
                $format);

        } elseif ($roleCategory == 'Parent') {
            $format = (!$informal? '%1$s ' : '') . ($reverse? '%3$s, %2$s' : '%2$s %3$s');
            $output = sprintf($format, $title, $preferredName, $surname);
        } elseif ($roleCategory == 'Student') {
            $format = $reverse ? '%2$s, %1$s' : '%1$s %2$s';
            $output = sprintf($format, $preferredName, $surname);
        }

        return trim($output, ' ');
    }
}