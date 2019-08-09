<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/08/2019
 * Time: 14:39
 */

namespace App\Twig\Extension;

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class SettingExtension
 * @package App\Twig\Extension
 */
class SettingExtension extends AbstractExtension
{

    /**
     * getFunctions
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getSettingByScope', [$this, 'getSettingByScope']),
            new TwigFunction('hasSettingByScope', [$this, 'hasSettingByScope']),
        ];
    }

    /**
     * getSettingByScope
     * @param string $name
     * @param null $default
     */
    public function getSettingByScope(string $scope, string $name, bool $returnRow = false)
    {
        return ProviderFactory::create(Setting::class)->getSettingByScope($scope, $name, $returnRow);
    }

    /**
     * hasSettingByScope
     * @param string $scope
     * @param string $name
     * @return bool
     */
    public function hasSettingByScope(string $scope, string $name): bool
    {
        return ProviderFactory::create(Setting::class)->hasSettingByScope($scope, $name);
    }
}