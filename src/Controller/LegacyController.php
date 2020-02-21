<?php

namespace App\Controller;

use App\Entity\Hook;
use App\Entity\I18n;
use App\Entity\Setting;
use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use App\Manager\PageManager;
use App\Provider\ProviderFactory;
use App\Twig\Sidebar\Flash;
use App\Twig\Sidebar\Register;
use App\Twig\SidebarContent;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class LegacyController
 * @package App\Controller
 */
class LegacyController extends AbstractController
{
    /**
     * @Route("/", name="legacy")
     */
    public function index(Request $request, LegacyManager $manager, GibbonManager $gibbonManager)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('home');
        }

        $error = $gibbonManager->execute();
        if ($error instanceof Response){
            return $error;
        }

        $result = $manager->execute($request, $gibbonManager->getPage());

        if ($result instanceof Response){
            return $result;
        }

        return $this->render('index.html.twig',
            [
                'controller_name' => 'LegacyController',
                'manager' => $result,
            ]
        );
    }

    /**
     * localeSwitch
     * @param string $i18n
     * @param Request $request
     * @return Response
     * @Route("/locale/{i18n}/switch/", name="locale_switch")
     * @IsGranted("ROLE_USER")
     */
    public function localeSwitch(string $i18n, Request $request)
    {
        ProviderFactory::create(I18n::class)->setLanguageSession($request->getSession(), ['code' => $i18n]);
        return $this->forward(LegacyController::class.'::index');
    }

    /**
     * @Route("/home/", name="home")
     * @param Request $request
     * @param SidebarContent $sidebar
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function home(Request $request, SidebarContent $sidebar, PageManager $pageManager)
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY'))
            return $this->redirectToRoute('legacy');

        if ($request->getContentType() !== 'json')
            return $this->render('react_base.html.twig',
                [
                    'page' => $pageManager,
                ]
            );

        if ($request->query->get('timeout') === 'true')
            $this->addFlash('warning', 'Your session expired, so you were automatically logged out of the system.');

        $sidebar->addContent(new Flash());

        if (ProviderFactory::create(Setting::class)->getSettingByScopeAsBoolean('User Admin', 'enablePublicRegistration'))
            $sidebar->addContent(new Register());

        return new JsonResponse(array_merge(['content' => trim($this->renderView('default/welcome.html.twig',
            [
                'hooks' => ProviderFactory::getRepository(Hook::class)->findBy(['type' => 'Public Home Page'],['name' => 'ASC']),
            ]
        ))], $sidebar->toArray()));
    }
}
