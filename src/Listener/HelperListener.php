<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Listener;

use App\Manager\GibbonManager;
use App\Manager\LegacyConnectionFactory;
use App\Manager\MessageManager;
use App\Provider\ProviderFactory;
use App\Util\EntityHelper;
use App\Util\ErrorHelper;
use App\Util\FileHelper;
use App\Util\GlobalHelper;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Kookaburra\UserAdmin\Util\SecurityHelper;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Kookaburra\UserAdmin\Util\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

/**
 * Class HelperListener
 *
 * This class simply pre loads static helpers.
 *
 * @package App\Listener
 */
class HelperListener implements EventSubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * HelperListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RouterInterface $router
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $stack
     * @param Environment $twig
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     * @param GibbonManager $gibbonManager
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MessageManager $messageManager,
        AuthorizationCheckerInterface $authorizationChecker,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        RequestStack $stack,
        TranslatorInterface $translator,
        Environment $twig,
        ContainerInterface $container,
        LoggerInterface $logger,
        UrlGeneratorHelper $urlGeneratorHelper,
        GibbonManager $gibbonManager,
        UserPasswordEncoderInterface $encoder,
        ParameterBagInterface $params
    ) {
        if ($container->hasParameter('installed') && $container->getParameter('installed')) {
            $eh = new EntityHelper(new ProviderFactory($entityManager, $messageManager, $authorizationChecker, $router, $stack));
            $lcf = new LegacyConnectionFactory();
            $gibbonManager->setContainer($container);
            $eh = new ErrorHelper($twig);
            $gh = new GlobalHelper($stack, $params);
            $sh = new SecurityHelper($logger, $authorizationChecker);
            $uh = new UserHelper($tokenStorage, $encoder);
            new TranslationsHelper($translator);
            new AcademicYearHelper($stack);
            new FileHelper($container->getParameter('absoluteURL'), $container->getParameter('upload_path'), $container->get('kernel')->getPublicDir());
        }
        $this->container = $container;
        $this->router = $router;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['gibbonInitiate', 128],
        ];
    }

    /**
     * gibbonInitiate
     * @param RequestEvent $event
     */
    public function gibbonInitiate(RequestEvent $event)
    {
        if (false !== strpos($event->getRequest()->getPathInfo(), '/install/'))
            return;

        $installed =  $this->container->hasParameter('installed') ? (bool) $this->container->getParameter('installed') : false;

        if ($installed)
            return;

        $installationProcess = $this->container->hasParameter('installation') ? $this->container->getParameter('installation') : [];

        if (!isset($installationProcess['status']) || $installationProcess['status'] === 'check') {
            $request = $this->container->get('request_stack')->getCurrentRequest();
            $event->setResponse(new RedirectResponse($request->server->get('REQUEST_SCHEME') . '://' . $request->server->get('SERVER_NAME') . '/install/installation/check/'));
            return ;
        }
        if ($installationProcess['status'] === 'mysql') {
            $event->setResponse(new RedirectResponse($this->router->generate('install__installation_mysql')));
            return ;
        }
        if ($installationProcess['status'] === 'build') {
            $event->setResponse(new RedirectResponse($this->router->generate('install__installation_build')));
            return ;
        }
        if ($installationProcess['status'] === 'system') {
            $event->setResponse(new RedirectResponse($this->router->generate('install__installation_system')));
            return ;
        }
    }
}
