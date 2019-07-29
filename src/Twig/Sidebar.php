<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 16:43
 */
namespace App\Twig;

use App\Manager\AddressManager;
use App\Util\UserHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Sidebar
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var array|boolean
     */
    private $content;

    /**
     * Sidebar constructor.
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        if (null === $this->request)
            $this->request = $this->stack->getCurrentRequest();

        return $this->request;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        if (null === $this->session)
            $this->session = $this->getRequest()->getSession();

        return $this->session;
    }

    private function execute(): void
    {
        $user = UserHelper::getSecurityUser();
        $this->content = false;

        //Show role switcher if user has more than one role
        if (null !== $user) {
            if (count($user->getAllRolesAsArray()) > 1 && false === $this->getRequest()->get('address')) {

                $this->addContent('role_switcher', true);
            }
            if ($this->getRequest()->attributes->get('menuModuleItems'))
            {
                $this->addContent('menuModule', $this->getRequest()->attributes->get('menuModuleItems'));
            }
        }
    }

    /**
     * hasContent
     * @return bool
     */
    public function hasContent(): bool
    {
        if (null === $this->content)
            $this->execute();

        return is_array($this->content) ? true : false;
    }

    /**
     * getContent
     * @return array|bool
     */
    public function getContent()
    {
        if (null === $this->content)
            $this->execute();

        return $this->content;
    }

    /**
     * Content.
     *
     * @param array|bool $content
     * @return Sidebar
     */
    public function addContent($name, $value)
    {
        if (null === $this->content || false === $this->content)
            $this->content = [];
        $this->content[$name] = $value;
        return $this;
    }
}