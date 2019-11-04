<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/11/2019
 * Time: 06:07
 */

namespace App\Form\Entity;

/**
 * Class SearchAny
 * @package App\Form\Entity
 */
class SearchAny
{
    /**
     * @var string|null
     */
    private $search;

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * Search.
     *
     * @param string|null $search
     * @return SearchAny
     */
    public function setSearch(?string $search): SearchAny
    {
        $this->search = $search;
        return $this;
    }
}