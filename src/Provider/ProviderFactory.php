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


use Doctrine\ORM\EntityManagerInterface;

class ProviderFactory
{
    /**
     * EntityTrait constructor.
     * @param EntityManagerInterface $entityManager
     * @param MessageManager $messageManager
     * @param AuthorizationCheckerInterface $authorizationChecker
     * @param TranslatorInterface $translator
     * @param RouterInterface $router
     * @throws \Exception
     */
    public function __construct(EntityManagerInterface $entityManager, MessageManager $messageManager,
                                AuthorizationCheckerInterface $authorizationChecker,
                                RouterInterface $router)
    {
        $this->entityManager = $entityManager;
        $this->messageManager = $messageManager;
        self::$entityRepository = $this->getRepository();
        $this->authorizationChecker = $authorizationChecker;
        $this->router = $router;
        if (method_exists($this, 'additionalConstruct'))
            $this->additionalConstruct();
    }
}