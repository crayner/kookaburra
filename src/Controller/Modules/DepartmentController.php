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

use App\Container\ContainerManager;
use App\Entity\Course;
use App\Entity\CourseClass;
use App\Entity\Department;
use App\Entity\DepartmentResource;
use App\Entity\DepartmentStaff;
use App\Entity\Setting;
use App\Entity\Unit;
use App\Form\Modules\Departments\CourseOverviewType;
use App\Form\Modules\Departments\EditType;
use App\Form\Modules\Departments\ResourceTypeManager;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar;
use App\Util\SecurityHelper;
use Doctrine\DBAL\Driver\PDOException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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

        ProviderFactory::create(CourseClass::class)->getMyClasses($this->getUser(), $sidebar);
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

        if ($this->isGranted('ROLE_ROUTE', ['departments__course_class_details']))
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

    /**
     * courseClass
     * @Route("/{department}/course/{course}/class/{class}/details", name="course_class_details")
     */
    public function courseClass(CourseClass $class, Sidebar $sidebar, Request $request, ?Department $department = null, ?Course $course = null)
    {
        if(!$class instanceof CourseClass) {
            return $this->render('components/error.html.twig', [
                'error' => 'The specified record does not exist.',
            ]);
        }

        if(null === $course || $class->getCourse() !== $course) {
            $course = $class->getCourse();
        }

        if (null === $department || $department !== $course->getDepartment()) {
            $department = $course->getDepartment();
        }

        $role = ProviderFactory::create(DepartmentStaff::class)->getRole($department, $this->getUser());

        $extra = '';
        if (in_array($role, ['Coordinator','Assistant Coordinator','Teacher (Curriculum)','Teacher']) && $course->getSchoolYear()->getId() !== $request->getSession()->get('schoolYear')->getId()) {
            $extra = ' '.$course->getSchoolYear()->getName();
        }

        $classActions = [];
        // Attendance
        if ($class->isAttendance() && $this->isGranted('ROLE_ACTION', ["/modules/Attendance/attendance_take_byCourseClass.php"])) {
            $classActions[] = [
                'name' => 'Attendance',
                'url'  => 'legacy',
                'params' => ['q' => '/modules/Attendance/attendance_take_byCourseClass.php', 'gibbonCourseClassID' => $class->getId()],
                'icon' => 'fas fa-user-friends text-gray-600 fa-fw fa-4x',
            ];
        }
        // Planner
        if ($this->isGranted('ROLE_ACTION', ['/modules/Planner/planner.php'])) {
            $classActions[] = [
                'name' => 'Planner',
                'url'  => 'legacy',
                'params' => ['q' => '/modules/Planner/planner.php', 'gibbonCourseClassID' => $class->getId(), 'viewBy' => 'class'],
                'icon' =>  'far fa-calendar-alt text-gray-600 fa-fw fa-4x',
            ];
        }
        // Markbook
        if (SecurityHelper::getHighestGroupedAction('/modules/Markbook/markbook_view.php') === 'View Markbook_allClassesAllData') {
            $classActions[] = [
                'name' => 'Markbook',
                'url'  => 'legacy',
                'params' => ['q' => '/modules/Markbook/markbook_view.php', 'gibbonCourseClassID' => $class->getId()],
                'icon' => 'fas fa-th fa-fw text-gray-600 fa-4x',
            ];
        }
        // Homework
        if ($this->isGranted('ROLE_ACTION', ['/modules/Planner/planner_deadlines.php'])) {
            $classActions[] = [
                'name' => 'Homework',
                'url'  => 'legacy',
                'params' => ['q' => '/modules/Planner/planner_deadlines.php', 'gibbonCourseClassIDFilter' => $class->getId()],
                'icon' => 'fas fa-clipboard-check text-gray-600 fa-fw fa-4x',
            ];
        }
        // Internal Assessment
        if ($this->isGranted('ROLE_ACTION', ['/modules/Formal Assessment/internalAssessment_write.php'])) {
            $classActions[] = [
                'name' => 'Internal Assessment',
                'url'  => 'legacy',
                'params' => ['q' => '/modules/Formal Assessment/internalAssessment_write.php', 'gibbonCourseClassID' => $class->getId()],
                'icon' => 'fas fa-file-alt text-gray-600 fa-fw fa-4x',
            ];
        }



        return $this->render('modules/departments/course_class.html.twig',
            [
                'department' => $department,
                'course' => $course,
                'class' => $class,
                'extra' => $extra,
                'classAction' => $classActions,
            ]
        );
    }

    /**
     * edit
     * @param Department $department
     * @Route("/{department}/edit/", name="edit")
     * @IsGranted("ROLE_ROUTE")
     */
    public function edit(Request $request, ResourceTypeManager $resourceTypeManager, ContainerManager $manager, ?Department $department = null)
    {
        $form = $this->createForm(EditType::class, $department,
            [
                'resource_manager' => $resourceTypeManager,
                'resource_delete_route' => $this->generateUrl('departments__resource_delete', ['resource' => '__id__', 'department' => '__department__']),
                'action' => $this->generateUrl('departments__edit', ['department' => $department->getId()])
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($department);
            $em->flush();
            $form = $this->createForm(EditType::class, $department, ['resource_manager' => $resourceTypeManager]);
        }

        $manager->singlePanel($form->createView(), 'DepartmentEdit');

        return $this->render('modules/departments/edit.html.twig', [
            'department' => $department,
        ]);
    }

    /**
     * deleteResource
     * @param DepartmentResource $departmentResource
     * @Route("/{department}/resource/{resource}/delete/", name="resource_delete")
     * @Security("is_granted('ROLE_ROUTE', ['departments__edit'])")
     * @return JsonResponse
     */
    public function deleteResource(DepartmentResource $resource, Department $department, TranslatorInterface $translator, ContainerManager $manager, ResourceTypeManager $resourceTypeManager)
    {
        $data = [];
        $data['errors'] = [];
        $data['form'] = [];
        if ($department !== $resource->getDepartment()) {
            $data['errors'][] = ['class' => 'error', 'message' => $translator->trans('Your request failed because your inputs were invalid.', [], 'gibbon')];
            return JsonResponse::create($data, 200);
        }
        if (!ProviderFactory::create(DepartmentStaff::class)->getRole($department, $this->getUser())) {
            $data['errors'][] = ['class' => 'error', 'message' =>  $translator->trans('Your request failed because you do not have access to this action.', [], 'gibbon')];
            return JsonResponse::create($data, 200);
        } else {
            try {
                $em = $this->getDoctrine()->getManager();
//                $em->remove($resource);
  //              $em->flush();
            } catch (PDOException $e) {
                $data['errors'][] = ['class' => 'error', 'message' =>  $translator->trans('Your request failed due to a database error.', [], 'gibbon')];
                return JsonResponse::create($data, 200);
            }
            $form = $this->createForm(EditType::class, $department,
                [
                    'resource_manager' => $resourceTypeManager,
                    'resource_delete_route' => $this->generateUrl('departments__resource_delete', ['resource' => '__id__', 'department' => '__department__']),
                    'action' => $this->generateUrl('departments__edit', ['department' => $department->getId()])
                ]
            );

            $manager->singlePanel($form->createView(), 'DepartmentEdit');
            $data['form'] = $manager->getContainers()->get('formContent')['panels']->get('single')['form']['children']['resources'];
            if ($data['errors'] === []) {
                $data['errors'][] = ['class' => 'success', 'message' =>  $translator->trans('Your request was completed successfully.', [], 'gibbon')];
            }
        }

        //JSON Response required.
        return JsonResponse::create($data, 200);

    }
}