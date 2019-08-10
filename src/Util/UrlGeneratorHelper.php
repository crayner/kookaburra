<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 08:48
 */

namespace App\Util;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UrlGeneratorHelper
{
    /**
     * @var UrlGeneratorInterface
     */
    private static $generator;

    /**
     * RouterHelper constructor.
     *
     * This class should only be used in the legacy area to generate routes to new code.
     * @param UrlGeneratorInterface $generator
     */
    public function __construct(UrlGeneratorInterface $generator)
    {
        self::$generator = $generator;
    }
    
    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $relative
     *
     * @return string
     */
    public static function getPath($name, $parameters = [], $relative = false)
    {
        return self::$generator->generate($name, $parameters, $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH);
    }

    /**
     * @param string $name
     * @param array  $parameters
     * @param bool   $schemeRelative
     *
     * @return string
     */
    public static function getUrl($name, $parameters = [], $schemeRelative = false)
    {
        return self::$generator->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }

}