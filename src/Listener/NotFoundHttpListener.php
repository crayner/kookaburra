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
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class NotFoundHttpListener
 * @package App\Listener
 */
class NotFoundHttpListener implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * NotFoundHttpListener constructor.
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     */
    public function __construct(TranslatorInterface $translator, RouterInterface $router)
    {
        $this->translator = $translator;
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

    /**
     * exception
     * @param ExceptionEvent $event
     */
    public function exception(ExceptionEvent $event)
    {
        if ($event->getException() instanceof NotFoundHttpException
            && strpos($event->getException()->getMessage(), 'object not found by the @ParamConverter annotation.') !== false
            && strpos($event->getRequest()->get('_route'), 'notification') === 0)
        {
            $event->getRequest()->getSession()->getBag('flashes')->add('error', 'return.error1');
            $response = new RedirectResponse($this->router->generate('notifications_manage'));
            $event->setResponse($response);
        }
        if ($event->getException() instanceof NotFoundHttpException
            && strpos($event->getException()->getMessage(), '@ParamConverter annotation') !== false
            && $event->getRequest()->getContentType() === 'json')
        {
            $data = [];
            $data['errors'][] = ['class' => 'error', 'message' => $this->translator->trans('The selected record does not exist, or you do not have access to it.', [], 'messages')];
            $data['status'] = 'error';

            $response = new JsonResponse($data, 201);
            $event->setResponse($response);
        }
    }
}