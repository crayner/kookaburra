<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 15:36
 */

namespace App\Twig;

use App\Entity\CourseClass;
use App\Entity\CourseClassPerson;
use App\Util\TranslationsHelper;
use Kookaburra\UserAdmin\Entity\FamilyAdult;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\SystemAdmin\Entity\Role;
use App\Entity\StudentEnrolment;
use App\Provider\ProviderFactory;
use Kookaburra\SystemAdmin\Provider\RoleProvider;
use App\Util\CacheHelper;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class FastFinder
 * @package App\Twig
 */
class FastFinder implements ContentInterface
{
    use ContentTrait;

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
        if (!SecurityHelper::isGranted('IS_AUTHENTICATED_FULLY'))
            return;

        $highestActionClass = SecurityHelper::getHighestGroupedAction('/modules/Planner/planner.php');

        $this->addAttribute('roleCategory', RoleProvider::getRoleCategory($this->getSession()->get('gibbonRoleIDCurrent')));

        $this->addAttribute('trans_fastFind', $this->translate('Fast Finder', [], 'messages'));
        $this->addAttribute('trans_fastFindActions', $this->translate('Actions', [], 'messages')
            .(SecurityHelper::isActionAccessible('/modules/Planner/planner.php') && $highestActionClass !== 'Lesson Planner_viewMyChildrensClasses' ? ', ' . $this->translate('Classes', [], 'messages') : '')
            .(SecurityHelper::isActionAccessible('/modules/students/student_view.php') ? ', '.$this->translate('Students', [], 'messages') : '')
            .(SecurityHelper::isActionAccessible('/modules/Staff/staff_view.php') ? ', '.$this->translate('Staff', [], 'messages') : ''));
        $this->addAttribute('trans_enrolmentCount', $this->getAttribute('roleCategory') === 'Staff' ? $this->translate('Total Student Enrolment:', [], 'messages') . ' ' .ProviderFactory::getRepository(StudentEnrolment::class)->getStudentEnrolmentCount($this->getSession()->get('AcademicYearID')) : '');
        $this->addAttribute('themeName', $this->getSession()->get('gibbonThemeName'));
        $this->addAttribute('trans_placeholder', $this->translate('Start typing a name...', [], 'messages'));
        $this->addAttribute('trans_close', $this->translate('Close', [], 'messages'));

        $actions = $this->getFastFinderActions($this->getSession()->get('gibbonRoleIDCurrent'));

        $classes = $this->accessibleClasses();

        $staff = $this->accessibleStaff();
        $students = $this->accessibleStudents();
        $fastFindChoices = [];
        $fastFindChoices[] = ['title' => $this->translate('Actions'), 'suggestions' => $actions, 'prefix' => $this->translate('Action')];
        $fastFindChoices[] = ['title' => $this->translate('Classes'), 'suggestions' => $classes, 'prefix' => $this->translate('Class')];
        $fastFindChoices[] = ['title' => $this->translate('Staff'), 'suggestions' => $staff, 'prefix' => $this->translate('Staff')];
        $fastFindChoices[] = ['title' => $this->translate('Students'), 'suggestions' => $students, 'prefix' => $this->translate('Student')];
        $this->addAttribute('fastFindChoices', $fastFindChoices);
    }

    /**
     * getFastFinderActions
     *
     * @param int $roleID
     * @return mixed
     * @throws \Exception
     */
    public function getFastFinderActions(?int $roleID): array
    {
        $actions = [];
        CacheHelper::setSession($this->getSession());
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
                    $classes = ProviderFactory::getRepository(CourseClass::class)->findAccessibleClasses($this->getSession()->get('academicYear'), '');
                } else {
                    $classes = ProviderFactory::getRepository(CourseClassPerson::class)->findAccessibleClasses($this->getSession()->get('academicYear'), $this->getToken()->getToken()->getUser()->getPerson(), '');
                }
            }
            CacheHelper::setCacheValue('fastFinderClasses', $classes ?: []);
        } else {
            $classes = CacheHelper::getCacheValue('fastFinderClasses');
        }
        return $classes;
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
                    $students = ProviderFactory::getRepository(FamilyAdult::class)->findStudentsOfParentFastFinder($this->getToken()->getToken()->getUser()->getPerson(), '', $this->getSession()->get('academicYear'));
                } elseif ($highestActionStudent == 'View Student Profile_my') {
                    $person = ProviderFactory::getRepository(Person::class)->find(2761);
                    $students = [];
                    $student = [];
                    $student['id'] = 'Stu-' . $person->getId();
                    $student['text'] = ' - ' . $person->getSurname() . ', ' . $person->getPreferredName();
                    foreach($person->getStudentEnrolments() AS $se) {
                        if ($se->getSchoolYear()->getId() === $this->getSession()->get('academicYear')->getId()) {
                            $rollGroup = $se->getRollGroup();
                            break;
                        }
                    }
                    $student['text'] .= ' (' . ($rollGroup ? $rollGroup->getName() : '') . ', ' . $person->getStudentID() . ')';
                    $student['search'] = $person->getUsername() . ' ' . $person->getFirstName() . ' ' . $person->getEmail();
                    $students[] = $student;
                } else {
                    $students = ProviderFactory::getRepository(Person::class)->findStudentsForFastFinder($this->getSession()->get('academicYear'), '');
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
        return TranslationsHelper::translate($key, $params, $domain);
    }
}