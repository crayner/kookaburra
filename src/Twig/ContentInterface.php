<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 12:03
 */

namespace App\Twig;

/**
 * Interface ContentInterface
 * @package App\Twig
 */
interface ContentInterface
{
    /**
     * execute
     */
    public function execute(): void;

    /**
     * getContent
     * @return bool
     */
    public function getContent(): bool;

    /**
     * addContent
     * @param $name
     * @param $value
     * @return self
     */
    public function addContent(string $name, $value): ContentInterface;

}