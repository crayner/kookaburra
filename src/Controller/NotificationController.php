<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/08/2019
 * Time: 07:38
 */

namespace App\Controller;

use App\Entity\Notification;
use App\Manager\NotificationTrayManager;
use App\Provider\ProviderFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class NotificationController
 * @package App\Controller
 */
class NotificationController extends AbstractController
{
    /**
     * index
     * @param Request $request
     * @param NotificationTrayManager $notification
     * @return JsonResponse
     * @Route("/api/notification/refresh/", name="notification_refresh")
     */
    public function index(Request $request, NotificationTrayManager $notification)
    {
        return new JsonResponse($notification->execute($this->getUser()), 200);
    }

    /**
     * manage
     * @param Request $request
     * @Route("/notifications/manage/", name="notifications_manage")
     * @IsGranted("ROLE_USER")
     */
    public function manage(Request $request)
    {
        $notifications = ProviderFactory::getRepository(Notification::class)->findByPersonStatus($this->getUser()->getPerson());
        $archived = ProviderFactory::getRepository(Notification::class)->findByPersonStatus($this->getUser()->getPerson(), 'Archived');

        return $this->render('notifications/notification_manage.html.twig',
            [
                'new' => $notifications,
                'archived' => $archived,
            ]
        );
    }

    /**
     * deleteAll
     * @Route("/notifications/delete/all/", name="notifications_delete_all")
     * @IsGranted("ROLE_USER")
     */
    public function deleteAll()
    {
        $notifications = ProviderFactory::getRepository(Notification::class)->findByPerson($this->getUser()->getPerson());

        $em = $this->getDoctrine()->getManager();

        foreach($notifications as $notification)
            $em->remove($notification);
        $em->flush();

        $this->addFlash('success', 'return.success0');

        return $this->forward(NotificationController::class.'::manage');
    }

    /**
     * delete
     * @Route("/notifications/{notification}/delete/", name="notification_delete")
     * @IsGranted("ROLE_USER")
     */
    public function delete(Notification $notification)
    {
        if ($notification->getPerson() === $this->getUser()->getPerson())
        {
            $em = $this->getDoctrine()->getManager();

            $em->remove($notification);
            $em->flush();
            $this->addFlash('success', 'return.success0');
        } else {
            $this->addFlash('error', 'return.error1');
        }

        return $this->forward(NotificationController::class.'::manage');
    }

    /**
     * action
     * @param Notification $notification
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/notifications/{notification}/action/", name="notification_action")
     * @IsGranted("ROLE_USER")
     */
    public function action(Notification $notification)
    {
        if ($notification->getPerson() === $this->getUser()->getPerson()) {
            $notification->setStatus('Archived');

            $em = $this->getDoctrine()->getManager();

            $em->persist($notification);
            $em->flush();

            $url = $notification->getActionLink();
            return new RedirectResponse($url);
        }
        $this->addFlash('error', 'return.error2');

        return $this->forward(NotificationController::class.'::manage');
    }
}