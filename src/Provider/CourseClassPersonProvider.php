<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 14:58
 */

namespace App\Provider;

use App\Entity\CourseClassPerson;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use App\Security\SecurityUser;
use App\Twig\Sidebar;

class CourseClassPersonProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = CourseClassPerson::class;
}