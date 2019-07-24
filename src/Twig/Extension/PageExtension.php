<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/07/2019
 * Time: 16:52
 */

namespace App\Twig\Extension;


use App\Entity\I18n;
use App\Provider\I18nProvider;
use App\Provider\ProviderFactory;
use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PageExtension extends AbstractExtension
{
    /**
     * @var I18nProvider
     */
    private $i18nProvider;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * PageExtension constructor.
     *
     * @param ProviderFactory $factory
     * @param SessionInterface $session
     */
    public function __construct(ProviderFactory $factory, SessionInterface $session, RequestStack $stack)
    {
        $this->i18nProvider = $factory::create(I18n::class);
        $this->session = $session;
        $this->stack = $stack;
    }

    /**
     * getFunctions
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('rightToLeft', [$this, 'findLocaleRightToLeft']),
            new TwigFunction('houseIDLogo', [$this, 'houseIDLogo']),
            new TwigFunction('minorLinks', [$this, 'minorLinks']),
            new TwigFunction('notificationTray', [$this, 'notificationTray']),
            new TwigFunction('sidebar', [$this, 'sidebar']),
            new TwigFunction('content', [$this, 'content']),
            new TwigFunction('breadcrumbs', [$this, 'breadcrumbs']),
            new TwigFunction('alerts', [$this, 'alerts']),
            new TwigFunction('getThemeName', [$this, 'getThemeName']),
        ];
    }

    /**
     * findLocaleRightToLeft
     * @return bool
     * @throws \Exception
     */
    public function findLocaleRightToLeft(): bool
    {
        try {
            return $this->i18nProvider->getRepository()->findLocaleRightToLeft();
        } catch (ConnectionException $e) {
            return false;
        }
    }

    /**
     * houseIDLogo
     * @return string|null
     */
    public function houseIDLogo(): ?string
    {
        return $this->session->has('gibbonHouseIDLogo') ? $this->session->get('gibbonHouseIDLogo') : null;
    }

    /**
     * minorLinks
     * @return string|null
     */
    public function minorLinks(): ?string
    {
        return null;
    }

    /**
     * notificationTray
     * @return string|null
     */
    public function notificationTray(): ?string
    {
        return null;
    }

    /**
     * sideBar
     * @return string|boolean
     */
    public function sidebar()
    {
        $request = $this->stack->getCurrentRequest();
        if (strpos($request->getPathInfo(), '/install') === 0)
            return true;
        return false;
    }

    /**
     * content
     * @return bool
     */
    public function content()
    {
        return false;
    }

    /**
     * breadcrumbs
     * @return bool
     */
    public function breadcrumbs()
    {
        return false;
    }

    /**
     * breadcrumbs
     * @return bool
     */
    public function alerts()
    {
        return [];
    }

    /**
     * getThemeName
     * @return string
     */
    public function getThemeName(): string
    {
        return $this->session->get('gibbonThemeName', 'default');
    }
}