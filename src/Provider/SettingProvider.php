<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 10:27
 */

namespace App\Provider;

use App\Entity\Setting;
use App\Manager\Traits\EntityTrait;

class SettingProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Setting::class;
}