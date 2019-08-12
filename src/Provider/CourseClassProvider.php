<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 12/08/2019
 * Time: 14:56
 */

namespace App\Provider;

use App\Entity\CourseClass;
use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use App\Security\SecurityUser;
use App\Twig\Sidebar;

/**
 * Class CourseClassProvider
 * @package App\Provider
 */
class CourseClassProvider implements EntityProviderInterface
{
    use EntityTrait;

    private $entityName = CourseClass::class;

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
            $result = $this->getRepository()->findByPersonSchoolYear($this->getSession()->get('schoolYear'), $person->getPerson());
        elseif ($person instanceof Person)
            $result = $this->getRepository()->findByPersonSchoolYear($this->getSession()->get('schoolYear'), $person);

        if (count($result) > 0 && null !== $sidebar)
            $sidebar->addExtra('myClasses', $result);

        return $result ?: [];
    }
}