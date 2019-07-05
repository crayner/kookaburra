<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/07/2019
 * Time: 15:33
 */

namespace App\Provider;

use App\Entity\Staff;
use App\Manager\Traits\EntityTrait;

class StaffProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Staff::class;
}