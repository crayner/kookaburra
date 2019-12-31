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
 * Date: 13/11/2019
 * Time: 06:18
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;

/**
 * Class Flash
 * @package App\Twig\Sidebar
 */
class Flash implements SidebarContentInterface
{
    use SidebarContentTrait;

    /**
     * @var string
     */
    private $name = 'Flash';

    /**
     * @var string
     */
    private $position = 'top';

    /**
     * @var int
     */
    private $priority = 1;

    /**
     * @var bool
     */
    private $closeMessage = false;

    /**
     * render
     * @param array $options
     * @return string
     */
    public function render(array $options): string
    {
        return $this->getTwig()->render('components/sidebar/flashes.html.twig', [
            'close_message' => $this->isCloseMessage(),
        ]);
    }

    /**
     * @return bool
     */
    public function isCloseMessage(): bool
    {
        return $this->closeMessage;
    }

    /**
     * CloseMessage.
     *
     * @param bool $closeMessage
     * @return Flash
     */
    public function setCloseMessage(bool $closeMessage): Flash
    {
        $this->closeMessage = $closeMessage;
        return $this;
    }
}