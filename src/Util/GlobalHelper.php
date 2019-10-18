<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/07/2019
 * Time: 14:50
 */

namespace App\Util;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Yaml\Yaml;

/**
 * Class GlobalHelper
 * @package App\Util
 */
class GlobalHelper
{
    /**
     * @var RequestStack
     */
    private static $stack;

    /**
     * GlobalHelper constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        self::$stack = $stack;
    }

    private static $page;

    /**
     * @return mixed
     */
    public static function getPage()
    {
        return self::$page;
    }

    /**
     * @param mixed $page
     */
    public static function setPage($page): void
    {
        self::$page = $page;
    }

    /**
     * getIPAddress
     * @return array|bool|false|string
     */
    public static function getIPAddress(string $ip = null)
    {
        if (null !== $ip)
            return $ip;

        $request = self::getRequest();
        if ($request->server->has('HTTP_CLIENT_IP'))
            return $request->server->get('HTTP_CLIENT_IP');
        else if($request->server->has('HTTP_X_FORWARDED_FOR'))
            return $request->server->get('HTTP_X_FORWARDED_FOR');
        else if($request->server->has('HTTP_X_FORWARDED'))
            return $request->server->get('HTTP_X_FORWARDED');
        else if($request->server->has('HTTP_FORWARDED_FOR'))
            return $request->server->get('HTTP_FORWARDED_FOR');
        else if($request->server->has('HTTP_FORWARDED'))
            return $request->server->get('HTTP_FORWARDED');
        else if($request->server->has('REMOTE_ADDR'))
            return $request->server->get('REMOTE_ADDR');

        return false;
    }

    /**
     * @var Request
     */
    private static $request;

    /**
     * getRequest
     * @return Request|null
     */
    private static function getRequest(bool $master = false): ?Request
    {
        if (!$master && (null === self::$request || self::$request !== self::$stack->getCurrentRequest()))
            self::$request = self::$stack->getCurrentRequest();
        if ($master && self::$request !== self::$stack->getMasterRequest())
            self::$request = self::$stack->getMasterRequest();
        return self::$request;
    }


    /**
     * readKookaburraYaml
     * @return array
     */
    public static function readKookaburraYaml(): array
    {
        $configFile = __DIR__ . '/../../config/packages/kookaburra.yaml';
        if (realpath($configFile))
            return Yaml::parse(file_get_contents($configFile));
        return [];
    }

    /**
     * writeKookaburraYaml
     * @param array $config
     */
    public static function writeKookaburraYaml(array $config): void
    {
        $configFile = __DIR__ . '/../../config/packages/kookaburra.yaml';
        if (realpath($configFile))
            file_put_contents($configFile, Yaml::dump($config, 8));
    }

    /**
     * num2alpha
     * @param $n
     * @return string
     */
    public static function num2alpha($n)
    {
        for ($r = ""; $n >= 0; $n = intval($n / 26) - 1) {
            $r = chr($n%26 + 0x41) . $r;
        }
        return $r;
    }
}