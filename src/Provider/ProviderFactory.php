<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 28/06/2019
 * Time: 15:01
 */

namespace App\Provider;

use App\Manager\MessageManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ProviderFactory
{
    /**
     * @var EntityManagerInterface
     */
    private static $entityManager;

    /**
     * @var MessageManager
     */
    private static $messageManager;

    /**
     * @var AuthorizationCheckerInterface
     */
    private static $authorizationChecker;

    /**
     * @var RouterInterface
     */
    private static $router;

    /**
     * @var array
     */
    private static $instances;

    /**
     * @var ProviderFactory
     */
    private static $factory;

    /**
     * EntityTrait constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param RouterInterface $router
     * @throws \Exception
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        MessageManager $messageManager,
        AuthorizationCheckerInterface $authorizationChecker,
        RouterInterface $router)
    {
        self::$entityManager = $entityManager;
        self::$messageManager = $messageManager;
        self::$authorizationChecker = $authorizationChecker;
        self::$router = $router;
        self::$factory = $this;
    }

    /**
     * getProvider
     * @param string $entityName
     * @return EntityProviderInterface
     * @throws ProviderException
     */
    public function getProvider(string $entityName): EntityProviderInterface
    {
        return self::create($entityName);
    }

    /**
     * getRepository
     * @param string $entityName
     * @return ObjectRepository
     */
    public static function getRepository(string $entityName): ObjectRepository
    {
        return self::$entityManager->getRepository($entityName);
    }

    /**
     * create
     * @param string $entityName
     * @return ObjectRepository
     * @throws ProviderException
     */
    public static function create(string $entityName): EntityProviderInterface
    {
        //The $entityName could be the plain name or the namespace name of the entity.
        // App\Entity\Module or Module
        $entityName = basename($entityName);

        if (isset(self::$instances[$entityName])) {
            return self::$instances[$entityName];
        }

        $providerName = 'App\Provider\\' . $entityName . 'Provider';
        if (class_exists($providerName)) {
            self::$instances[$entityName] = new $providerName(self::$factory);
            return self::$instances[$entityName];
        }

        throw new ProviderException(sprintf('The Entity Provider for the "%s" entity is not available.', $entityName));
    }

    /**
     * @return EntityManagerInterface
     */
    public static function getEntityManager(): EntityManagerInterface
    {
        return self::$entityManager;
    }

    /**
     * @return MessageManager
     */
    public static function getMessageManager(): MessageManager
    {
        return self::$messageManager;
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    public static function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return self::$authorizationChecker;
    }

    /**
     * @return RouterInterface
     */
    public static function getRouter(): RouterInterface
    {
        return self::$router;
    }
}