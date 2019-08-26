<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/08/2019
 * Time: 08:28
 */

namespace App\Form\Entity;

/**
 * Class ResetPassword
 * @package App\Form\Entity
 */
class ResetPassword
{
    /**
     * @var null|string
     */
    private $current;

    /**
     * @var null|string
     */
    private $raw;

    /**
     * @return string|null
     */
    public function getCurrent(): ?string
    {
        return $this->current;
    }

    /**
     * Current.
     *
     * @param string|null $current
     * @return ResetPassword
     */
    public function setCurrent(?string $current): ResetPassword
    {
        $this->current = $current;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRaw(): ?string
    {
        return $this->raw;
    }

    /**
     * Raw.
     *
     * @param string|null $raw
     * @return ResetPassword
     */
    public function setRaw(?string $raw): ResetPassword
    {
        $this->raw = $raw;
        return $this;
    }
}