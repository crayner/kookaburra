<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 13/11/2019
 * Time: 15:35
 */

namespace App\Twig\Sidebar;

use App\Twig\SidebarContentInterface;
use App\Twig\SidebarContentTrait;

/**
 * Class Message
 * @package App\Twig\Sidebar
 */
class Message implements SidebarContentInterface
{
    use SidebarContentTrait;

    /**
     * @var string
     */
    private $name = 'Message';

    /**
     * @var int
     */
    private $priority = 5;

    /**
     * @var string
     */
    private $position = 'top';

    /**
     * render
     * @param array $options
     * @return string
     */
    public function render(array $options): string
    {
        return $this->getTwig()->render('components/sidebar/message.html.twig', [
            'message' => $this,
        ]);
    }

    /**
     * @var string|array|null
     */
    private $message;

    /**
     * @var string
     */
    private $class = 'info';

    /**
     * @return array|string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * setMessage
     * @param string|array $message
     * @param array|null $options
     * @param string $domain
     * @return $this
     */
    public function setMessage($message, ?array $options = null, string $domain = 'messages')
    {
        if (is_array($options) && is_string($message))
            $message = [$message,$options,$domain];

        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * Class.
     *
     * @param string $class
     * @return Message
     */
    public function setClass(string $class): Message
    {
        $this->class = $class;
        return $this;
    }

}