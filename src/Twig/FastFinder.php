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
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use App\Provider\RoleProvider;
use App\Util\SecurityHelper;
use Symfony\Component\Routing\RouterInterface;
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
     * execute
     * @throws \Exception
     */
    public function execute(): void
    {
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
}