<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 15:36
 */

namespace App\Twig;

use App\Entity\StudentEnrolment;
use App\Provider\ProviderFactory;
use App\Provider\RoleProvider;
use App\Util\SecurityHelper;
use Gibbon\Forms\Form;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Class FastFinder
 * @package App\Twig
 */
class FastFinder implements ContentInterface
{
    use ContentTrait;

    /**
     * execute
     * @throws \Exception
     */
    public function execute(): void
    {
        $form = Form::create('fastFinder', $this->getSession()->get('absoluteURL').'/finder/redirect/', 'get');
        $form->setClass('blank fullWidth');

        $form->addHiddenValue('address', $this->getSession()->get('address'));

        $row = $form->addRow();
        $row->addFinder('fastFinderSearch')
            ->fromAjax($this->getSession()->get('absoluteURL').'/index_fastFinder_ajax.php')
            ->setClass('w-full text-white')
            ->setParameter('hintText', __('Start typing a name...'))
            ->setParameter('noResultsText', __('No results'))
            ->setParameter('searchingText', __('Searching...'))
            ->setParameter('tokenLimit', 1)
            ->addValidation('Validate.Presence', 'failureMessage: " "');
        $row->addSubmit(__('Go'));

        $highestActionClass = SecurityHelper::getHighestGroupedAction('/modules/Planner/planner.php');

        $templateData = [
            'roleCategory'        => RoleProvider::getRoleCategory($this->getSession()->get('gibbonRoleIDCurrent')),
            'studentIsAccessible' => SecurityHelper::isActionAccessible('/modules/students/student_view.php'),
            'staffIsAccessible'   => SecurityHelper::isActionAccessible('/modules/Staff/staff_view.php'),
            'classIsAccessible'   => SecurityHelper::isActionAccessible('/modules/Planner/planner.php') && $highestActionClass !== 'Lesson Planner_viewMyChildrensClasses',
            'form'                => $form->getOutput(),
        ];

        $templateData['enrolmentCount'] = ProviderFactory::getRepository(StudentEnrolment::class)->getStudentEnrolmentCount($this->getSession()->get('gibbonSchoolYearID'));

        $this->addContent('fastFinder', $templateData);
    }

}