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
use App\Entity\Module;
use App\Entity\Role;
use App\Entity\StudentEnrolment;
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use App\Provider\RoleProvider;
use App\Security\SecurityUser;
use App\Util\CacheHelper;
use App\Util\SecurityHelper;
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
            'action'                => $this->getRouter()->generate('finder_redirect'),
            'roleCategory'          => RoleProvider::getRoleCategory($this->getSession()->get('gibbonRoleIDCurrent')),
        ];

        $templateData['trans_fastFind'] = $this->getTranslator()->trans('Fast Finder', [], 'gibbon');
        $templateData['trans_fastFindActions'] = $this->getTranslator()->trans('Actions', [], 'gibbon');
        $templateData['trans_fastFindActions'] .= SecurityHelper::isActionAccessible('/modules/Planner/planner.php') && $highestActionClass !== 'Lesson Planner_viewMyChildrensClasses' ? ', '.$this->getTranslator()->trans('Classes', [], 'gibbon') : '';
        $templateData['trans_fastFindActions'] .= SecurityHelper::isActionAccessible('/modules/students/student_view.php') ? ', '.$this->getTranslator()->trans('Students', [], 'gibbon') : '';
        $templateData['trans_fastFindActions'] .= SecurityHelper::isActionAccessible('/modules/Staff/staff_view.php') ? ', '.$this->getTranslator()->trans('Staff', [], 'gibbon') : '';
        $templateData['trans_enrolmentCount'] = $templateData['roleCategory'] === 'Staff' ? $this->getTranslator()->trans('Total Student Enrolment:', [], 'gibbon') . ' ' .ProviderFactory::getRepository(StudentEnrolment::class)->getStudentEnrolmentCount($this->getSession()->get('gibbonSchoolYearID')) : '';
        $templateData['themeName'] = $this->getSession()->get('gibbonThemeName');

        $templateData['actions'] = $this->getFastFinderActions($this->getSession()->get('gibbonRoleIDCurrent'));

        $templateData['classes'] = $this->accessibleClasses();

        $this->getScriptManager()->addAppProp('fastFinder', $templateData);
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
    public function getFastFinderActions(int $roleID) {

        if (CacheHelper::isStale('fastFinderActions'))
        {
            // Get the accessible actions for the current user
            $role = ProviderFactory::getRepository(Role::class)->find($roleID);
            $actions = ProviderFactory::getRepository(Module::class)->findFastFinderActions($role);
            foreach($actions as $q=>$row)
            {
                $actions[$q]['name'] = $this->getTranslator()->trans($row['name'], [], 'gibbon');
            }
            CacheHelper::setCacheValue('fastFinderActions', $actions, 10);
        } else {
            $actions = CacheHelper::getCacheValue('fastFinderActions');
        }
        return $actions;
    }

    /**
     * accessibleClasses
     * @throws \Exception
     */
    public function accessibleClasses()
    {
        if (CacheHelper::isStale('fastFinderClasses')) {
            $classIsAccessible = false;
            $highestActionClass = SecurityHelper::getHighestGroupedAction('/modules/Planner/planner.php');
            if (SecurityHelper::isActionAccessible('/modules/Planner/planner.php') && $highestActionClass !== 'Lesson Planner_viewMyChildrensClasses') {
                $classIsAccessible = true;
            }
            // CLASSES
            if ($classIsAccessible || true) {
                if ($highestActionClass === 'Lesson Planner_viewEditAllClasses' || $highestActionClass === 'Lesson Planner_viewAllEditMyClasses') {
                    $classes = ProviderFactory::getRepository(CourseClass::class)->findAccessibleClasses($this->getSession()->get('schoolYear'));
                } else {
                    $classes = ProviderFactory::getRepository(CourseClassPerson::class)->findAccessibleClasses($this->getSession()->get('schoolYear'), $this->getToken()->getToken()->getUser()->getPerson());
                }
            }
            CacheHelper::setCacheValue('fastFinderClasses', $classes);
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
}