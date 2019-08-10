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

use App\Entity\CourseClassPerson;
use App\Entity\Department;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar;
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
            $this->addFlash('error', 'You do not have access to this action.');
            return $this->redirectToRoute('home');
        }

        ProviderFactory::create(CourseClassPerson::class)->getMyClasses($this->getUser(), $sidebar);
        return $this->render('modules/departments/list.html.twig',
            [
                'departments' => ProviderFactory::getRepository(Department::class)->findBy([],['name' => 'ASC']),
            ]
        );
    }
}