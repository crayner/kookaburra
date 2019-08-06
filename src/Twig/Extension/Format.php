<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/08/2019
 * Time: 15:38
 */

namespace App\Twig\Extension;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Format extends AbstractExtension
{

    /**
     * getFunctions
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('formatSetting', [$this, 'formatSetting']),
        ];
    }
    public function formatSetting(string $name): string
    {
        return \App\Util\Format::getSetting($name);
    }
}
