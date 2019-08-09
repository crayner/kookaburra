<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/08/2019
 * Time: 13:38
 */

namespace App\Controller;

use App\Entity\NotificationEvent;
use App\Entity\Person;
use App\Entity\PersonField;
use App\Form\Registration\PublicType;
use App\Mailer\NotificationMailer;
use App\Provider\ProviderFactory;
use App\Util\SecurityHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationController
 * @package App\Controller
 */
class RegistrationController extends AbstractController
{
    /**
     * publicRegistration
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/registration/public/", name="registration_public")
     */
    public function publicRegistration(Request $request, NotificationMailer $mailer)
    {
        $options['dateFormat'] = $request->getSession()->get(['i18n', 'dateFormat']);

        $person = new Person();

        ProviderFactory::create(NotificationEvent::class)->setSender($mailer);

        $options['customFields'] = ProviderFactory::create(PersonField::class)->getCustomFields(null, null, null, null, null, null, true);

        $form = $this->createForm(PublicType::class, $person->mergeFields($options['customFields']), $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            ProviderFactory::create(Person::class)->handleRegistration($form);
        }

        return $this->render('registration/public.html.twig',
            [
                'form' => $form->createView(),
                'passwordPolicy' => SecurityHelper::getPasswordPolicy(),
            ]
        );
    }
}