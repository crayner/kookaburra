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
 * Date: 8/11/2019
 * Time: 16:28
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

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
     */
    public function render(array $options): string
    {
        try {
            return $this->getTwig()->render('installation/welcome.html.twig', $options);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return '';
        }
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        return [
            'content' => $this->render([]),
        ];
    }
}