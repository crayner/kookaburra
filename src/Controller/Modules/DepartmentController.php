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
use App\Entity\Unit;
use App\Form\Modules\Departments\CourseOverviewType;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar;
use App\Util\SecurityHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DepartmentController
 * @package App\Controller\Modules
 * @Route("/departments", name="departments__")
 */
class DepartmentController extends AbstractController
{
    /**
     * list
     * @Route("/list/", name="list")
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
     * @Route("/{department}/details/", name="details")
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

    /**
     * course
     * @param Department $department
     * @param Course $course
     * @param Sidebar $sidebar
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/{department}/course/{course}/details/", name="course_details")
     */
    public function course(Department $department, Course $course, Sidebar $sidebar, Request $request)
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

        if(!$course instanceof Course) {
            return $this->render('components/error.html.twig', [
                'error' => 'The specified record does not exist.',
            ]);
        }

        $role = ProviderFactory::create(DepartmentStaff::class)->getRole($department, $this->getUser());

        $extra = '';
        if (in_array($role, ['Coordinator','Assistant Coordinator','Teacher (Curriculum)','Teacher']) && $course->getSchoolYear()->getId() !== $request->getSession()->get('schoolYear')->getId()) {
            $extra = ' '.$course->getSchoolYear()->getName();
        }

        $units = ProviderFactory::getRepository(Unit::class)->findBy(['active' => 'Y', 'course' => $course],['ordering' => 'ASC', 'name' => 'ASC']);

        if ($this->isGranted('ROLE_ACTION', ['/modules/Departments/department_course_class.php']))
            $sidebar->addExtra('courseClasses', ['course' => $course, 'department' => $department]);

        return $this->render('modules/departments/course.html.twig',
            [
                'department' => $department,
                'course' => $course,
                'extra' => $extra,
                'role' => $role,
                'units' => $units,
            ]
        );
    }

    /**
     * courseEdit
     * @param Request $request
     * @param Department $department
     * @param Course $course
     * @Route("/{department}/course/{course}/edit/", name="course_edit")
     * @IsGranted("ROLE_ROUTE")
     */
    public function courseEdit(Request $request, Department $department, Course $course)
    {
        if (!$department instanceof Department) {
            return $this->render('components/error.html.twig', [
                'error' => 'The specified record does not exist.',
            ]);
        }

        if(!$course instanceof Course) {
            return $this->render('components/error.html.twig', [
                'error' => 'The specified record does not exist.',
            ]);
        }

        $role = ProviderFactory::create(DepartmentStaff::class)->getRole($department, $this->getUser());

        if (!in_array($role, ['Coordinator','Assistant Coordinator','Teacher (Curriculum)'])) {
            return $this->render('components/error.html.twig', [
                'error' => 'The selected record does not exist, or you do not have access to it.',
            ]);
        }

        $form = $this->createForm(CourseOverviewType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $provider = ProviderFactory::create(Course::class);
            $provider->setEntity($course);
            $provider->saveEntity();
        }

        return $this->render('modules/departments/course_edit.html.twig',
            [
                'department' => $department,
                'course' => $course,
                'form' => $form->createView(),
            ]
        );
    }
}