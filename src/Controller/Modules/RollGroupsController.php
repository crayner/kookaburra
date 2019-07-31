<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 10:52
 */

namespace App\Controller\Modules;

use App\Entity\RollGroup;
use App\Entity\SchoolYear;
use App\Form\Modules\RollGroups\DetailStudentSortType;
use App\Manager\Module\RollGroup\RollGroupTable;
use App\Provider\ProviderFactory;
use App\Twig\TableViewManager;
use App\Util\SecurityHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RollGroupsController
 * @package App\Controller\Modules
 * @Route("/roll/groups", name="roll_groups__")
 */
class RollGroupsController extends AbstractController
{
    /**
     * list
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/list/", name="list")
     * @Security("is_granted('ROLE_ROUTE')")
     */
    public function list(Request $request, TranslatorInterface $translator)
    {
        $rollGroups = ProviderFactory::getRepository(RollGroup::class)->findBy(['schoolYear' => ProviderFactory::getRepository(SchoolYear::class)->find($request->getSession()->get('gibbonSchoolYearID', 0))],['name' => 'ASC']);

        $table = new TableViewManager(['formatTutors' => $translator->trans('Main Tutor', [], 'gibbon')]);

        $table->addColumn('name','Name');
        $table->addColumn('formatTutors', 'Form Tutors');
        $table->addColumn('spaceName', 'Room')->setHeadClass('column hidden sm:table-cell')->setBodyClass('p-2 sm:p-3 hidden sm:table-cell');
        if ($this->getUser()->getPrimaryRole() && $this->getUser()->getPrimaryRole()->getCategory() == "Staff") {
            $table->addColumn('studentCount', 'Students')->setHeadClass('column hidden md:table-cell')->setBodyClass('p-2 sm:p-3 hidden md:table-cell');
        }
        $table->addColumn('website', 'Website')->setHeadClass('column hidden md:table-cell')->setBodyClass('p-2 sm:p-3 hidden md:table-cell');
        $table->addColumn('actionColumn', 'Actions')->setStyle("width: '1%'")->addAction('View', 'view', 'roll_groups__detail', ['rollGroup' => 'Id']);

        return $this->render('modules/roll_groups/list.html.twig',
            [
                'table_data' => $rollGroups,
                'table' => $table,
            ]
        );
    }

    /**
     * detail
     * @param RollGroup $rollGroup
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     * @Route("/{rollGroup}/detail/", name="detail")
     * @Security("is_granted('ROLE_ROUTE')")
     */
    public function detail(RollGroup $rollGroup, Request $request)
    {
        $softError = (!$rollGroup instanceof RollGroup) ? 'The selected record does not exist, or you do not have access to it.' : '';

        $canViewConfidential = true;

        $canPrint = SecurityHelper::isActionAccessible('/modules/Students/report_students_byRollGroup_print.php');

        $highestAction = SecurityHelper::getHighestGroupedAction('/modules/Students/student_view_details.php');

        $canViewStudents = ($highestAction == 'View Student Profile_brief' || $highestAction == 'View Student Profile_full' || $highestAction == 'View Student Profile_fullNoNotes');

        $sortBy = new \stdClass();
        $sortBy->sortBy = 'rollOrder, surname, preferredName';
        $sortBy->confidential = $canViewStudents;
        $form = $this->createForm(DetailStudentSortType::class, $sortBy);
        $form->handleRequest($request);

        return $this->render('modules/roll_groups/details.html.twig',
            [
                'softError' => $softError,
                'rollGroup' => $rollGroup,
                'staffView' => SecurityHelper::isActionAccessible('/modules/Staff/staff_view_details.php'),
                'sortBy' => $sortBy,
                'form' => $form->createView(),
                'canPrint' => $canPrint,
                'canViewStudents' => $canViewStudents,
            ]
        );
    }
}
