<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 16/12/2018
 * Time: 16:49
 */
namespace App\Manager;

/**
 * Interface EntityInterface
 * @package App\Manager
 */
interface EntityInterface
{
    /**
     * getId
     * @return int|null
     */
    public function getId(): ?int;
}