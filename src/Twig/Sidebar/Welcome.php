<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/11/2019
 * Time: 16:28
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;

/**
 * Class Welcome
 * @package App\Twig
 */
class Welcome implements SidebarContentInterface
{
    use SidebarContentTrait;

    /**
     * @var string
     */
    private $name = 'Welcome';

    /**
     * render
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(array $options): string
    {
        return $this->getTwig()->render('installation/welcome.html.twig', $options);
    }
}