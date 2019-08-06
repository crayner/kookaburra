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

use App\Manager\NotificationTrayManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return $this->render('notifications/notification_manage.html.twig',
            [

            ]
        );
    }
}