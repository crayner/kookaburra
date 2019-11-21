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
use Kookaburra\UserAdmin\Entity\Person;
use App\Manager\Traits\EntityTrait;
use Kookaburra\Departments\Twig\MyClasses;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use App\Twig\SidebarContent;

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
     * @param SidebarContent|null $sidebar
     * @return array
     * @throws \Exception
     */
    public function getMyClasses($person, ?SidebarContent $sidebar = null)
    {
        $result = [];
        if ($person instanceof SecurityUser)
            $result = $this->getRepository()->findByPersonSchoolYear($this->getSession()->get('schoolYear'), $person->getPerson());
        elseif ($person instanceof Person)
            $result = $this->getRepository()->findByPersonSchoolYear($this->getSession()->get('schoolYear'), $person);

        if (count($result) > 0 && null !== $sidebar) {
            $myClasses = new MyClasses();
            $sidebar->addContent($myClasses->setClasses($result));
        }

        return $result ?: [];
    }
}