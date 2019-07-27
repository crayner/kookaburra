<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 08:43
 */

namespace App\Manager\Entity;


class BreadCrumbItem
{
    /**
     * @var null|string
     */
    private $name;

    /**
     * @var null|string
     */
    private $uri;

    /**
     * BreadCrumbItem constructor.
     * @param array $crumb
     */
    public function __construct(array $crumb = [])
    {
        if ([] !== $crumb)
            $this->setName($crumb['name'])->setUri($crumb['uri']);
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param string|null $name
     * @return BreadCrumbItem
     */
    public function setName(?string $name): BreadCrumbItem
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }

    /**
     * Uri.
     *
     * @param string|null $uri
     * @return BreadCrumbItem
     */
    public function setUri(?string $uri): BreadCrumbItem
    {
        $this->uri = $uri;
        return $this;
    }
}