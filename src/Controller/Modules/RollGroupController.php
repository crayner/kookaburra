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
use App\Provider\ProviderFactory;
use App\Twig\TableViewManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RollGroupController
 * @package App\Controller\Modules
 * @Route("/roll/group", name="roll_group_")
 */
class RollGroupController extends AbstractController
{
    /**
     * list
     * @Route("/list/", name="list")
     * @Security("is_granted('ROLE_ACTION', ['/modules/Roll Groups/rollGroups.php'])")
     */
    public function list(Request $request, TranslatorInterface $translator)
    {

        $rollGroups = ProviderFactory::getRepository(RollGroup::class)->findBy(['schoolYear' => ProviderFactory::getRepository(SchoolYear::class)->find($request->getSession()->get('gibbonSchoolYearID', 0))],['name' => 'ASC']);

        $table = new TableViewManager(['formatTutors' => $translator->trans('Main Tutor')]);

        $table->addColumn('name','Name');
        $table->addColumn('formatTutors', 'Form Tutors');
        $table->addColumn('spaceName', 'Room')->setHeadClass('column hidden sm:table-cell')->setBodyClass('p-2 sm:p-3 hidden sm:table-cell');
        if ($this->getUser()->getPrimaryRole() && $this->getUser()->getPrimaryRole()->getCategory() == "Staff") {
            $table->addColumn('studentCount', 'Students')->setHeadClass('column hidden md:table-cell')->setBodyClass('p-2 sm:p-3 hidden md:table-cell');
        }
        $table->addColumn('website', 'Website')->setHeadClass('column hidden md:table-cell')->setBodyClass('p-2 sm:p-3 hidden md:table-cell');
        $table->addColumn('actionColumn', 'Actions')->setStyle("width: '1%'");

        return $this->render('modules/roll_groups/list.html.twig',
            [
                'table_data' => $rollGroups,
                'table' => $table,
            ]
        );
    }
}
