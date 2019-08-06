<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/08/2019
 * Time: 15:06
 */

namespace App\Provider;

use App\Entity\Notification;
use App\Manager\Traits\EntityTrait;

/**
 * Class NotificationProvider
 * @package App\Provider
 */
class NotificationProvider implements EntityProviderInterface
{
    use EntityTrait;
    /**
     * @var string
     */
    private $entityName = Notification::class;
}