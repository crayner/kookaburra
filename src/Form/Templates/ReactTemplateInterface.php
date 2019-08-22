<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/08/2019
 * Time: 08:49
 */

namespace App\Form\Templates;


interface ReactTemplateInterface
{
    /**
     * getStandardRow
     * @return array
     */
    public function getStandardRow(): array;

    /**
     * getHeaderRow
     * @return array
     */
    public function getHeaderRow(): array;

    /**
     * getSubmitRow
     * @return array
     */
    public function getSubmitRow(): array;

    /**
     * getParentStyle
     * @return array
     */
    public function getParentStyle(): array;
}