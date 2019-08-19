<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/07/2019
 * Time: 14:00
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class Password extends Constraint
{
    /**
     * @var bool
     */
    public $assumeCurrentUser = true;
}