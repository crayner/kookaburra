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
 * Date: 16/10/2019
 * Time: 16:46
 */

namespace App\Twig\Extension;

use App\Manager\MessageManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MessageExtension extends AbstractExtension
{
    /**
     * @var MessageManager
     */
    private $manager;

    /**
     * MessageExtension constructor.
     * @param MessageManager $manager
     */
    public function __construct(MessageManager $manager)
    {
        $this->manager = $manager;
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('getMessages', [$this->manager, 'getMessages']),
        ];
    }

}