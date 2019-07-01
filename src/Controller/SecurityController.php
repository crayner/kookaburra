<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\Security\AuthenticateType;
use App\Manager\LoginManager;
use App\Provider\ProviderFactory;
use App\Security\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * login
     * @param LoginManager $manager
     * @param AuthenticationUtils $authenticationUtils
     * @param ProviderFactory $repository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/login/", name="login", methods={"GET", "POST"})
     */
    public function login(LoginManager $manager, AuthenticationUtils $authenticationUtils, ProviderFactory $repository, Request $request)
    {
        $repository = $repository->getProvider(Person::class);
        if ($this->getUser() instanceof UserInterface && !$this->isGranted('ROLE_USER'))
            return $this->redirectToRoute('home');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $user = $repository->getRepository(Person::class)->loadUserByUsernameOrEmail($lastUsername) ?: new Person();
        $user->setUsername($lastUsername);
        $securityUser = new SecurityUser($user);

        return $this->redirectToRoute('home');

        $form = $this->createForm(AuthenticateType::class, $securityUser);

        return $this->render('Security\login.html.twig',
            [
                'form'      => $form->createView(),
                'manager'   => $manager,
                'fullForm'  => $form,
                'error'     => $error,
            ]
        );
    }
}
