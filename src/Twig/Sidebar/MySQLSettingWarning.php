<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/11/2019
 * Time: 17:08
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;

/**
 * Class MySQLSettingWarning
 * @package App\Twig
 */
class MySQLSettingWarning implements SidebarContentInterface
{
    use SidebarContentTrait;

    /**
     * @var string
     */
    private $name = 'MySQL Setting Warning';

    /**
     * render
     * @param array $options
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(array $options): string
    {
        return $this->getTwig()->render('installation/sql_setting_warning.html.twig', $options);
    }
}