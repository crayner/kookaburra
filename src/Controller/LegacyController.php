<?php

namespace App\Controller;

use App\Manager\GibbonManager;
use App\Manager\LegacyManager;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LegacyController extends AbstractController
{
    /**
     * @Route("/", name="legacy")
     */
    public function index(Request $request, LegacyManager $manager, ContainerInterface $container, GibbonManager $gibbonManager)
    {
        $gibbonManager->setContainer($container);
        $error = $gibbonManager->execute();
        if ($error instanceof Response){
            return $error;
        }
        $manager->execute($request);
        return $this->render('legacy/index.html.twig', [
            'controller_name' => 'LegacyController',
        ]);
    }
}
