<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 1/08/2019
 * Time: 14:10
 */

namespace App\Util;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CacheHelper
{
    /**
     * @var SessionInterface
     */
    private static $session;

    /**
     * @return SessionInterface
     */
    public static function getSession(): SessionInterface
    {
        return self::$session;
    }

    /**
     * setSession
     * @param SessionInterface $session
     */
    public static function setSession(SessionInterface $session)
    {
        self::$session = $session;
    }

    /**
     * isStale
     * @param string $name
     * @param int $interval
     * @return bool
     * @throws \Exception
     */
    public static function isStale(string $name, int $interval = 10): bool
    {
        $cacheTime = self::getSession()->get($name . '_cacheTime', null);
        if (null === $cacheTime || $cacheTime->getTimestamp() < self::intervalDateTime($interval)->getTimestamp())
            return true;
        return false;
    }

    /**
     * intervalDateTime
     * @param int $interval
     * @return \DateTime
     * @throws \Exception
     */
    public static function intervalDateTime(int $interval): \DateTime
    {
        return new \DateTime('- ' . strval($interval * 30 + rand(0, $interval * 60)) . ' Seconds');
    }

    /**
     * setCacheValue
     * @param string $name
     * @param $content
     * @param int $interval
     * @throws \Exception
     */
    public static function setCacheValue(string $name, $content, int $interval = 10)
    {
        self::getSession()->set($name, $content);
        self::getSession()->set($name.'_cacheTime', new \DateTime('+ '.$interval.' Minutes'));
    }

    /**
     * getCacheValue
     * @param string $name
     * @return mixed
     */
    public static function getCacheValue(string $name)
    {
        return self::getSession()->has($name) ? self::getSession()->get($name) : null;
    }
}