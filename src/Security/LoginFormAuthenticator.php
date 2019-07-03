<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * UserProvider: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Security;

use App\Entity\Person;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Doctrine\ORM\providerFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

/**
 * Class LoginFormAuthenticator
 * @package App\Security
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var ProviderFactory
     */
    private $providerFactory;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * LoginFormAuthenticator constructor.
     * @param ProviderFactory $providerFactory
     * @param RouterInterface $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(ProviderFactory $providerFactory, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->providerFactory = $providerFactory;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * supports
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        return 'login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * getCredentials
     * @param Request $request
     * @return array|mixed
     */
    public function getCredentials(Request $request)
    {
        $authenticate = $request->request->get('authenticate');
        if (null === $authenticate)
        {
            $authenticate['_username'] =   $request->request->get('username');
            $authenticate['_password'] =   $request->request->get('password');
            $authenticate['gibbonSchoolYearID'] = $request->request->get('gibbonSchoolYearID');
            $authenticate['address'] = $request->request->get('address');
            $authenticate['_token'] = 'legacy';
        }

        $credentials = [
            'email' => $authenticate['_username'],
            'password' => $authenticate['_password'],
            'csrf_token' => $authenticate['_token'],
            'gibbonSchoolYearID' => $authenticate['gibbonSchoolYearID'],
            'address' => $authenticate['address'],

        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * getUser
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return object|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if ('legacy' !== $credentials['csrf_token'] && !$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->providerFactory->getProvider(Person::class)->loadUserByUsername($credentials['email']);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    /**
     * checkCredentials
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * onAuthenticationSuccess
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response|null
     * @throws \Exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        //store the token blah blah blah
        $session = $request->getSession();
        $this->createUserSession($token->getUsername(), $session);

        $session->save();
        if ($targetPath = $this->getTargetPath($request, $providerKey))
            return new RedirectResponse($targetPath);

        return new RedirectResponse($this->getLoginUrl());
    }

    /**
     * getLoginUrl
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    public function createUserSession($username, $session) {

        $userData = $this->providerFactory::getRepository(Person::class)->findOneByUsername($username);
        if (null === $userData) {
            $userData = $this->providerFactory::getRepository(Person::class)->findOneByEmail($username);
        }
        $session->set('username', $username);
        $session->set('passwordStrong', $userData->getPasswordStrong());
        $session->set('passwordStrongSalt', $userData->getPasswordStrongSalt());
        $session->set('passwordForceReset', $userData->getPasswordForceReset());
        $session->set('gibbonPersonID', $userData->getId());
        $session->set('surname', $userData->getSurname());
        $session->set('firstName', $userData->getFirstName());
        $session->set('preferredName', $userData->getPreferredName());
        $session->set('officialName', $userData->getOfficialName());
        $session->set('email', $userData->getEmail());
        $session->set('emailAlternate', $userData->getEmailAlternate());
        $session->set('website', $userData->getWebsite());
        $session->set('gender', $userData->getGender());
        $session->set('status', $userData->getstatus());
        $primaryRole = $userData->getPrimaryRole();
        $session->set('gibbonRoleIDPrimary', $primaryRole ? $primaryRole->getId() : null);
        $session->set('gibbonRoleIDCurrent', $primaryRole ? $primaryRole->getId() : null);
        $session->set('gibbonRoleIDCurrentCategory', $primaryRole ? $primaryRole->getCategory() : null);
        $session->set('gibbonRoleIDAll', $this->providerFactory->getProvider(Role::class)->getRoleList($userData->getAllRoles()) );
        $session->set('image_240', $userData->getImage240());
        $session->set('lastTimestamp', $userData->getLastTimestamp());
        $session->set('calendarFeedPersonal', $userData->getcalendarFeedPersonal());
        $session->set('viewCalendarSchool', $userData->getviewCalendarSchool());
        $session->set('viewCalendarPersonal', $userData->getviewCalendarPersonal());
        $session->set('viewCalendarSpaceBooking', $userData->getviewCalendarSpaceBooking());
        $session->set('dateStart', $userData->getdateStart());
        $session->set('personalBackground', $userData->getpersonalBackground());
        $session->set('messengerLastBubble', $userData->getmessengerLastBubble());
        $session->set('gibboni18nIDPersonal', $userData->getI18nPersonal() ? $userData->getI18nPersonal()->getId() : null);
        $session->set('googleAPIRefreshToken', $userData->getgoogleAPIRefreshToken());
        $session->set('receiveNotificationEmails', $userData->getreceiveNotificationEmails());
        $session->set('gibbonHouseID', $userData->getHouse() ? $userData->getHouse()->getId() : null);

        //Deal with themes
        $session->set('gibbonThemeIDPersonal', $userData->getTheme() ? $userData->getTheme()->getId() : null);

        // Cache FF actions on login
    //    $session->cacheFastFinderActions($userData->getgibbonRoleIDPrimary']);
    }
}
