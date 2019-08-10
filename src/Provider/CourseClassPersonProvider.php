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

    /**
     * getMyClasses
     * @param Person|SecurityUser|string|null $person
     * @param Sidebar|null $sidebar
     * @return array
     * @throws \Exception
     */
    public function getMyClasses($person, ?Sidebar $sidebar = null)
    {
        $result = [];
        if ($person instanceof SecurityUser)
            $result = $this->getRepository()->getMyClasses($this->getSession()->get('schoolYear'), $person->getPerson());
        elseif ($person instanceof Person)
            $result = $this->getRepository()->getMyClasses($this->getSession()->get('schoolYear'), $person);

        if (count($result) > 0)
            $sidebar->addExtra('myClasses', $result);

        return $result ?: [];
    }
}