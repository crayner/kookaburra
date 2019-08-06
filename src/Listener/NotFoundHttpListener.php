<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/08/2019
 * Time: 17:28
 */

namespace App\Listener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class NotFoundHttpListener implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * NotFoundHttpListener constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['exception', 0],
        ];
    }

    public function exception(ExceptionEvent $event)
    {
        if ($event->getException() instanceof NotFoundHttpException && strpos($event->getException()->getMessage(), 'object not found by the @ParamConverter annotation.') !== false) {
            if (strpos($event->getRequest()->get('_route'), 'notification') === 0) {
                $event->getRequest()->getSession()->getBag('flashes')->add('error', 'return.error1');
                $response = new RedirectResponse($this->router->generate('notifications_manage'));
                $event->setResponse($response);
            }
        }
    }
}