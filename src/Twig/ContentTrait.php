<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 12:05
 */

namespace App\Twig;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Trait ContentTrait
 * @package App\Twig
 */
trait ContentTrait
{
    /**
     * @var array|boolean
     */
    private $content = false;


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
     * MainMenu constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * getContent
     * @return bool
     */
    public function getContent(): bool
    {
        if (false === $this->content)
            $this->execute();

        return $this->content;
    }

    /**
     * addContent
     * @param string $name
     * @param $value
     * @return ContentInterface
     */
    public function addContent(string $name, $value): ContentInterface
    {
        if (false === $this->content)
            $this->getRequest()->attributes->set(basename(get_class($this)), true);

        $this->content = true;
        $this->getRequest()->attributes->set($name, $value);

        return $this;
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
}