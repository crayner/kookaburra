<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 14:08
 */

namespace App\Controller\Modules;

use App\Entity\Course;
use App\Entity\CourseClassPerson;
use App\Entity\Department;
use App\Entity\DepartmentStaff;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar;
use App\Util\SecurityHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentController
 * @package App\Controller\Modules
 */
class DepartmentController extends AbstractController
{
    /**
     * list
     * @Route("/departments/list/", name="departments__list")
     */
    public function list(Sidebar $sidebar)
    {
        if (!$this->isGranted('ROLE_ROUTE') && !ProviderFactory::create(Setting::class)->getSettingByScopeAsBoolean('Departments', 'makeDepartmentsPublic')) {
            return $this->render('components/error.html.twig', [
                'error' => 'You do not have access to this action.',
            ]);
        }

        ProviderFactory::create(CourseClassPerson::class)->getMyClasses($this->getUser(), $sidebar);
        return $this->render('modules/departments/list.html.twig',
            [
                'departments' => ProviderFactory::getRepository(Department::class)->findBy([],['name' => 'ASC']),
            ]
        );
    }

    /**
     * details
     * @param Department $department
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/departments/{department}/details/", name="departments__details")
     */
    public function details(Department $department, Sidebar $sidebar)
    {
        if (!$this->isGranted('ROLE_ROUTE') && !ProviderFactory::create(Setting::class)->getSettingByScopeAsBoolean('Departments', 'makeDepartmentsPublic')) {
            return $this->render('components/error.html.twig', [
                'error' => 'You do not have access to this action.',
            ]);
        }

        if (!$department instanceof Department) {
            return $this->render('components/error.html.twig', [
                'error' => 'The specified record does not exist.',
            ]);
        }

        $role = ProviderFactory::create(DepartmentStaff::class)->getRole($department, $this->getUser());

        if (count(explode(',', $department->getSubjectListing())) > 0)
            $sidebar->addExtra('subjectList', explode(',', $department->getSubjectListing()));

        $courses = ProviderFactory::create(Course::class)->getByDepartment($department);

        if (count($courses) > 0)
            $sidebar->addExtra('courseList', ['courses' => $courses, 'department' => $department]);

        return $this->render('modules/departments/details.html.twig',
            [
                'department' => $department,
                'role' => $role,
                'canViewProfile' => SecurityHelper::isActionAccessible('/modules/Staff/staff_view_details.php'),
            ]
        );
    }
}