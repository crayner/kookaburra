<?php

namespace App\Controller;

use App\Entity\Hook;
use App\Entity\I18n;
use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use App\Provider\ProviderFactory;
use App\Security\SecurityUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\SmtpTransport;
use Symfony\Component\Routing\Annotation\Route;

class LegacyController extends AbstractController
{
    /**
     * @Route("/", name="legacy")
     * @Route("/", name="home")
     */
    public function index(Request $request, LegacyManager $manager, GibbonManager $gibbonManager)
    {
        if (! $this->getUser() instanceof SecurityUser) {
            return $this->render('default/welcome.html.twig',
                [
                    'hooks' => ProviderFactory::getRepository(Hook::class)->findBy(['type' => 'Public Home Page'],['name' => 'ASC']),
                ]
            );
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
     * @Route("/test")
     */
    public function test(MailerInterface $mailer)
    {

        dd($mailer);
    }
}
