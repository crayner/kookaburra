<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 19/12/2018
 * Time: 12:17
 */
namespace App\Util;

use App\Entity\Action;
use App\Entity\Module;
use App\Entity\Person;
use App\Entity\Setting;
use App\Exception\RouteConfigurationException;
use App\Provider\ActionProvider;
use App\Provider\ModuleProvider;
use App\Provider\ProviderFactory;
use Doctrine\DBAL\Driver\PDOException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityHelper
{
    /**
     * @var ActionProvider
     */
    private static $actionProvider;

    /**
     * @var ModuleProvider
     */
    private static $moduleProvider;

    /**
     * @var LoggerInterface
     */
    private static $logger;

    /**
     * @var AuthorizationCheckerInterface
     */
    private static $checker;

    /**
     * SecurityHelper constructor.
     * @param LoggerInterface $logger
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(
        LoggerInterface $logger,
        AuthorizationCheckerInterface $checker
    ) {
        self::$logger = $logger;
        self::$checker = $checker;
    }

    /**
     * @return ActionProvider
     */
    public static function getActionProvider(): ActionProvider
    {
        return ProviderFactory::create(Action::class);
    }

    /**
     * @return ModuleProvider
     */
    public static function getModuleProvider(): ModuleProvider
    {
        return ProviderFactory::create(Module::class);
    }

    /**
     * getHighestGroupedAction
     * @param string $address
     * @return bool|string
     * @throws \Exception
     */
    public static function getHighestGroupedAction(string $address)
    {
        $module = self::checkModuleReady($address);
        try {
            $result =  self::getActionProvider()->getRepository()->createQueryBuilder('a')
                ->select('a.name')
                ->join('a.permissions', 'p')
                ->where('a.URLList LIKE :actionName')
                ->setParameter('actionName', '%'.self::getActionName($address).'%')
                ->andWhere('a.module = :module')
                ->setParameter('module', $module)
                ->andWhere('p.role = :currentRole')
                ->setParameter('currentRole', UserHelper::getCurrentUser()->getPrimaryRole())
                ->orderBy('a.precedence', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
            return empty($result['name']) ? false :  $result['name'];
        } catch (PDOException $e) {
        } catch (\PDOException $e) {
        }
        return false;
    }

    /**
     * checkModuleReady
     * @param string $address
     * @return bool|Module
     */
    public static function checkModuleReady(string $address)
    {
        try {
            return self::getModuleProvider()->findOneBy(['name' => self::getModuleName($address), 'active' => 'Y']);
        } catch (PDOException $e) {
        } catch (\PDOException $e) {
        }

        return false;
    }

    /**
     * checkModuleRouteReady
     * @param string $address
     * @return bool|Module
     */
    public static function checkModuleRouteReady(string $route)
    {
        try {
            return self::getModuleProvider()->findOneBy(['name' => self::getModuleNameFromRoute($route), 'active' => 'Y']);
        } catch (PDOException $e) {
        } catch (\PDOException $e) {
        }

        return false;
    }

    /**
     * getModuleName
     * @param string $address
     * @return bool|string
     */
    public static function getModuleName(string $address)
    {
        return substr(substr($address, 9), 0, strpos(substr($address, 9), '/'));
    }

    /**
     * getActionName
     * @param $address
     * @return bool|string
     */
    public static function getActionName($address)
    {
        return substr($address, (10 + strlen(self::getModuleName($address))));
    }

    /**
     * getModuleName
     * @param string $address
     * @return bool|string
     */
    public static function getModuleNameFromRoute(string $route)
    {
        $route = self::splitRoute($route);
        return $route['module'];
    }

    /**
     * getActionName
     * @param $address
     * @return bool|string
     */
    public static function getActionNameFromRoute($route)
    {
        $route = self::splitRoute($route);
        return $route['action'];
    }

    public  static function splitRoute(string $route): array
    {
        $route = explode('__', $route);
        if (count($route) !== 2)
            throw new RouteConfigurationException(implode('__', $route));
        $route['module'] = ucwords(str_replace('_', ' ', $route[0]));
        $route['action'] = $route[1];
        return $route;
    }
    /**
     * isActionAccessible
     * @param string $address
     * @param string $sub
     * @return bool
     * @throws \Exception
     */
    public static function isActionAccessible(string $address, string $sub = '%', ?LoggerInterface $logger = null): bool
    {
        $action = '';
        $module = '';
        $role = '';
        //Check user is logged in
        if (UserHelper::getCurrentUser() instanceof Person) {
            //Check user has a current role set
            if (! empty(UserHelper::getCurrentUser()->getPrimaryRole())) {
                //Check module ready
                $module = self::checkModuleReady($address);
                $action = self::getActionName($address);
                if ($module instanceof Module) {
                    //Check current role has access rights to the current action.
                    try {
                        $role = UserHelper::getCurrentUser()->getPrimaryRole();
                        if (count(self::getActionProvider()->findByURLListModuleRole(
                                [
                                    'name' => "%".$action."%",
                                    "module" => $module,
                                    'role' => $role,
                                    'sub' => $sub,
                                ]
                            )) > 0)
                            return true;
                    } catch (PDOException $e) {
                    }
                } else {
                    self::$logger->warning(sprintf('No module was linked to the address "%s"', $address));
                }
            }
        } else {
            self::$logger->debug(sprintf('The user was not valid!' ));
        }
        self::$logger->debug(sprintf('The action "%s", role "%s" and sub-action "%s" combination is not accessible.', $action, $role, $sub ));

        return false;
    }

    /**
     * isRouteAccessible
     * @param string $route
     * @param string $sub
     * @param LoggerInterface|null $logger
     * @return bool
     * @throws \Exception
     */
    public static function isRouteAccessible(string $route, string $sub = '%', ?LoggerInterface $logger = null): bool
    {
        $action = '';
        $module = '';
        $role = '';
        //Check user is logged in
        if (UserHelper::getCurrentUser() instanceof Person) {
            //Check user has a current role set
            if (! empty(UserHelper::getCurrentUser()->getPrimaryRole())) {
                //Check module ready
                $module = self::checkModuleRouteReady($route);
                $action = self::getActionNameFromRoute($route);
                if ($module instanceof Module) {
                    //Check current role has access rights to the current action.
                    try {
                        $role = UserHelper::getCurrentUser()->getPrimaryRole();
                        if (count(self::getActionProvider()->findByURLListModuleRole(
                                [
                                    'name' => "%".$action."%",
                                    "module" => $module,
                                    'role' => $role,
                                    'sub' => $sub,
                                ]
                            )) > 0)
                            return true;
                    } catch (PDOException $e) {
                    }
                } else {
                    self::$logger->warning(sprintf('No module was linked to the address "%s"', $address));
                }
            }
        } else {
            self::$logger->debug(sprintf('The user was not valid!' ));
        }
        self::$logger->debug(sprintf('The action "%s", role "%s" and sub-action "%s" combination is not accessible.', $action, $role, $sub ));

        return false;
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    public static function getChecker(): AuthorizationCheckerInterface
    {
        return self::$checker;
    }

    /**
     * @var null|string
     */
    private static $passwordPolicy;

    /**
     * getPasswordPolicy
     * @return array
     */
    public static function getPasswordPolicy(): array
    {
        if (null !== self::$passwordPolicy)
            return self::$passwordPolicy;

        $output = [];
        $provider = ProviderFactory::create(Setting::class);
        $alpha = $provider->getSettingByScopeAsBoolean('System', 'passwordPolicyAlpha');
        $numeric = $provider->getSettingByScopeAsBoolean('System', 'passwordPolicyNumeric');
        $punctuation = $provider->getSettingByScopeAsBoolean('System', 'passwordPolicyNonAlphaNumeric');
        $minLength = $provider->getSettingByScopeAsInteger('System', 'passwordPolicyMinLength');

        if (!$alpha || !$numeric || !$punctuation || $minLength >= 0) {
            $output[] = 'The password policy stipulates that passwords must:';
            if ($alpha)
                $output[] = 'Contain at least one lowercase letter, and one uppercase letter.';
            if ($numeric)
                $output[] = 'Contain at least one number.';
            if ($punctuation)
                $output[] = 'Contain at least one non-alphanumeric character (e.g. a punctuation mark or space).';
            if ($minLength >= 0)
                $output[] = 'Must be at least %1$s characters in length.';
        }
        $output['minLength'] = $minLength;

        self::$passwordPolicy = $output;
        return self::$passwordPolicy;
    }

}