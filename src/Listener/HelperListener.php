<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
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
use App\Manager\SchoolYearManager;
use App\Provider\ActionProvider;
use App\Provider\FamilyAdultProvider;
use App\Provider\FamilyChildProvider;
use App\Provider\FamilyProvider;
use App\Provider\I18nProvider;
use App\Provider\ModuleProvider;
use App\Provider\PersonProvider;
use App\Provider\ProviderFactory;
use App\Provider\SchoolYearProvider;
use App\Provider\TimetableProvider;
use App\Util\EntityHelper;
use App\Util\ErrorHelper;
use App\Util\FormatHelper;
use App\Util\GlobalHelper;
use App\Util\LocaleHelper;
use App\Util\RelationshipHelper;
use App\Util\SchoolYearHelper;
use App\Util\SecurityHelper;
use App\Util\TimetableHelper;
use App\Util\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
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
     * HelperListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RouterInterface $router
     * @param TokenStorageInterface $tokenStorage
     * @param RequestStack $stack
     * @param TranslatorInterface $translator
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     * @throws \Exception
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MessageManager $messageManager,
        AuthorizationCheckerInterface $authorizationChecker,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage,
        RequestStack $stack,
//        TranslatorInterface $translator,
        Environment $twig,
        ContainerInterface $container,
        LoggerInterface $logger,
        GibbonManager $gibbonManager
    ) {
        $eh = new EntityHelper(new ProviderFactory($entityManager,$messageManager,$authorizationChecker,$router));
        $lcf = new LegacyConnectionFactory();
        $gibbonManager->setContainer($container);
        $eh = new ErrorHelper($twig);
        $gh = new GlobalHelper($stack);
        $sh = new SecurityHelper($logger, $authorizationChecker);
        $uh = new UserHelper($tokenStorage);
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
    public function gibbonInitiate(){}
}
