<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/11/2019
 * Time: 07:55
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;

/**
 * Class Login
 * @package App\Twig\SidebarContent
 */
class Login implements SidebarContentInterface
{
    use SidebarContentTrait;

    private $name = 'Login';

    private $position = 'top';

    public function render(array $options): string
    {
        return $this->getTwig()->render('default/sidebar/login.html.twig');
    }
}
