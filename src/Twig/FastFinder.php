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

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Entity\FamilyAdult;
use App\Entity\Module;
use App\Entity\Person;
use App\Entity\Role;
use App\Entity\StudentEnrolment;
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use App\Provider\RoleProvider;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use App\Util\CacheHelper;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FastFinder
 * @package App\Twig
 */
class FastFinder implements ContentInterface
{
    use ContentTrait;

    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var TokenStorageInterface
     */
    private $token;

    /**
     * execute
     * @throws \Exception
     */
    public function execute(): void
    {
        if (!$this->getToken()->getToken() || !$this->getToken()->getToken()->getUser() instanceof SecurityUser || !$this->getToken()->getToken()->getUser()->getPerson())
            return;

        $highestActionClass = SecurityHelper::getHighestGroupedAction('/modules/Planner/planner.php');

        $templateData = [
            'roleCategory'          => RoleProvider::getRoleCategory($this->getSession()->get('gibbonRoleIDCurrent')),
        ];

        $templateData['trans_fastFind'] = $this->getTranslator()->trans('Fast Finder', [], 'messages');
        $templateData['trans_fastFindActions'] = $this->getTranslator()->trans('Actions', [], 'messages');
        $templateData['trans_fastFindActions'] .= SecurityHelper::isActionAccessible('/modules/Planner/planner.php') && $highestActionClass !== 'Lesson Planner_viewMyChildrensClasses' ? ', '.$this->getTranslator()->trans('Classes', [], 'messages') : '';
        $templateData['trans_fastFindActions'] .= SecurityHelper::isActionAccessible('/modules/students/student_view.php') ? ', '.$this->getTranslator()->trans('Students', [], 'messages') : '';
        $templateData['trans_fastFindActions'] .= SecurityHelper::isActionAccessible('/modules/Staff/staff_view.php') ? ', '.$this->getTranslator()->trans('Staff', [], 'messages') : '';
        $templateData['trans_enrolmentCount'] = $templateData['roleCategory'] === 'Staff' ? $this->getTranslator()->trans('Total Student Enrolment:', [], 'messages') . ' ' .ProviderFactory::getRepository(StudentEnrolment::class)->getStudentEnrolmentCount($this->getSession()->get('gibbonSchoolYearID')) : '';
        $templateData['themeName'] = $this->getSession()->get('gibbonThemeName');
        $templateData['trans_placeholder'] = $this->getTranslator()->trans('Start typing a name...', [], 'messages');
        $templateData['trans_close'] = $this->getTranslator()->trans('Close', [], 'messages');

        $actions = $this->getFastFinderActions($this->getSession()->get('gibbonRoleIDCurrent'));

        $classes = $this->accessibleClasses();

        $staff = $this->accessibleStaff();
        $students = $this->accessibleStudents();
        $templateData['fastFindChoices'] = [];
        $templateData['fastFindChoices'][] = ['title' => $this->translate('Actions'), 'suggestions' => $actions, 'prefix' => $this->translate('Action')];
        $templateData['fastFindChoices'][] = ['title' => $this->translate('Classes'), 'suggestions' => $classes, 'prefix' => $this->translate('Class')];
        $templateData['fastFindChoices'][] = ['title' => $this->translate('Staff'), 'suggestions' => $staff, 'prefix' => $this->translate('Staff')];
        $templateData['fastFindChoices'][] = ['title' => $this->translate('Students'), 'suggestions' => $students, 'prefix' => $this->translate('Student')];

        $this->getScriptManager()->addAppProp('fastFinder', $templateData);
        $this->getScriptManager()->addEncoreEntryCSSFile('fastFinder');
    }

    /**
     * @return ScriptManager
     */
    public function getScriptManager(): ScriptManager
    {
        return $this->scriptManager;
    }

    /**
     * ScriptManager.
     *
     * @param ScriptManager $scriptManager
     * @return FastFinder
     */
    public function setScriptManager(ScriptManager $scriptManager): FastFinder
    {
        $this->scriptManager = $scriptManager;
        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Router.
     *
     * @param RouterInterface $router
     * @return FastFinder
     */
    public function setRouter(RouterInterface $router): FastFinder
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * Translator.
     *
     * @param TranslatorInterface $translator
     * @return FastFinder
     */
    public function setTranslator(TranslatorInterface $translator): FastFinder
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * getFastFinderActions
     *
     * @param int $roleID
     * @return mixed
     * @throws \Exception
     */
    public function getFastFinderActions(int $roleID)
    {
        $actions = [];
        if (CacheHelper::isStale('fastFinderActions'))
        {
            // Get the accessible actions for the current user
            $role = ProviderFactory::getRepository(Role::class)->find($roleID);
            $actions = ProviderFactory::getRepository(Module::class)->findFastFinderActions($role, '');
            CacheHelper::setCacheValue('fastFinderActions', $actions, 10);
        } else {
            $actions = CacheHelper::getCacheValue('fastFinderActions') ?: [];
        }
        return $actions;
    }

    /**
     * accessibleClasses
     * @throws \Exception
     */
    public function accessibleClasses()
    {
        $classes = [];
        if (CacheHelper::isStale('fastFinderClasses')) {
            $classIsAccessible = false;
            $highestActionClass = SecurityHelper::getHighestGroupedAction('/modules/Planner/planner.php');
            if (SecurityHelper::isActionAccessible('/modules/Planner/planner.php') && $highestActionClass !== 'Lesson Planner_viewMyChildrensClasses') {
                $classIsAccessible = true;
            }
            // CLASSES
            if ($classIsAccessible) {
                if ($highestActionClass === 'Lesson Planner_viewEditAllClasses' || $highestActionClass === 'Lesson Planner_viewAllEditMyClasses') {
                    $classes = ProviderFactory::getRepository(CourseClass::class)->findAccessibleClasses($this->getSession()->get('schoolYear'), '');
                } else {
                    $classes = ProviderFactory::getRepository(CourseClassPerson::class)->findAccessibleClasses($this->getSession()->get('schoolYear'), $this->getToken()->getToken()->getUser()->getPerson(), '');
                }
            }
            CacheHelper::setCacheValue('fastFinderClasses', $classes) ?: [];
        } else {
            $classes = CacheHelper::getCacheValue('fastFinderClasses');
        }
        return $classes;
    }

    /**
     * @return TokenStorageInterface
     */
    public function getToken(): TokenStorageInterface
    {
        return $this->token;
    }

    /**
     * Token.
     *
     * @param TokenStorageInterface $token
     * @return FastFinder
     */
    public function setToken(TokenStorageInterface $token): FastFinder
    {
        $this->token = $token;
        return $this;
    }

    /**
     * accessibleStaff
     * @return mixed
     * @throws \Exception
     */
    public function accessibleStaff()
    {
        $staff = [];
        if (CacheHelper::isStale('fastFinderStaff'))
        {
            // STAFF
            $staffIsAccessible = SecurityHelper::isActionAccessible('/modules/Staff/staff_view.php');

            if ($staffIsAccessible) {
                $staff = ProviderFactory::getRepository(Person::class)->findStaffForFastFinder('');
                CacheHelper::setCacheValue('fastFinderStaff', $staff);
            }
        } else {
            $staff = CacheHelper::getCacheValue('fastFinderStaff') ?: [];
        }
        return $staff;
    }

    /**
     * accessibleStudents
     * @return mixed
     * @throws \Exception
     */
    public function accessibleStudents()
    {
        // STUDENTS
        $students = [];
        if (CacheHelper::isStale('fastFinderStudents')) {
            $studentIsAccessible = SecurityHelper::isActionAccessible('/modules/students/student_view.php');
            $highestActionStudent = SecurityHelper::getHighestGroupedAction( '/modules/students/student_view.php');
            if ($studentIsAccessible) {
                if ($highestActionStudent === 'View Student Profile_myChildren') {
                    $students = ProviderFactory::getRepository(FamilyAdult::class)->findStudentsOfParentFastFinder($this->getToken()->getToken()->getUser()->getPerson(), '', $this->getSession()->get('schoolYear'));
                } elseif ($highestActionStudent == 'View Student Profile_my') {
                    $person = ProviderFactory::getRepository(Person::class)->find(2761);
                    $students = [];
                    $student = [];
                    $student['id'] = 'Stu-' . $person->getId();
                    $student['text'] = ' - ' . $person->getSurname() . ', ' . $person->getPreferredName();
                    foreach($person->getStudentEnrolments() AS $se) {
                        if ($se->getSchoolYear()->getId() === $this->getSession()->get('schoolYear')->getId()) {
                            $rollGroup = $se->getRollGroup();
                            break;
                        }
                    }
                    $student['text'] .= ' (' . ($rollGroup ? $rollGroup->getName() : '') . ', ' . $person->getStudentID() . ')';
                    $student['search'] = $person->getUsername() . ' ' . $person->getFirstName() . ' ' . $person->getEmail();
                    $students[] = $student;
                } else {
                    $students = ProviderFactory::getRepository(Person::class)->findStudentsForFastFinder($this->getSession()->get('schoolYear'), '');
                }
            }
            CacheHelper::setCacheValue('fastFinderStudents', $students);
        } else {
            $students = CacheHelper::getCacheValue('fastFinderStudents') ?: [];
        }
        return $students;
    }

    /**
     * translate
     * @param string $key
     * @param array|null $params
     * @param string|null $domain
     * @return string
     */
    private function translate(string $key, ?array $params = [], ?string $domain = 'messages'): string
    {
        return $this->getTranslator()->trans($key, $params, $domain);
    }
}