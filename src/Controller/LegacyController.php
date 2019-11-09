<?php

namespace App\Controller;

use App\Entity\Hook;
use App\Entity\I18n;
use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use App\Provider\ProviderFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function home()
    {
        if ($this->isGranted('IS_AUTHENTICATED_FULLY'))
            return $this->redirectToRoute('legacy');

        return $this->render('default/welcome.html.twig',
            [
                'hooks' => ProviderFactory::getRepository(Hook::class)->findBy(['type' => 'Public Home Page'],['name' => 'ASC']),
            ]
        );
    }
}
