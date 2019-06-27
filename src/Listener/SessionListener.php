<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/06/2019
 * Time: 13:22
 */

namespace App\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SessionListener implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::FINISH_REQUEST => ['onFinishRequest', 0],
        ];
    }

    /**
     * onFinishRequest
     * @param FinishRequestEvent $event
     */
    public function onFinishRequest(FinishRequestEvent $event) {
        $session = $event->getRequest()->getSession();
        $session->mergeLegacySession();
    }
}