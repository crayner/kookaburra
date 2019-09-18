<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/09/2019
 * Time: 14:50
 */

namespace App\Manager;


interface ReactPaginationInterface
{
    /**
     * execute
     *
     * Place all the logic to build your page here.
     * @return ReactPaginationInterface
     */
    public function execute(): ReactPaginationInterface;
}