<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LegacyController extends AbstractController
{
    /**
     * @Route("/", name="legacy")
     */
    public function index()
    {
        return $this->render('legacy/index.html.twig', [
            'controller_name' => 'LegacyController',
        ]);
    }
}
