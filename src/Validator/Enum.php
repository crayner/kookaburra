<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/07/2019
 * Time: 13:30
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Enum
 * @package App\Validator
 */
class Enum extends Constraint
{
    /**
     * @var bool
     * Allow and empty|null value on false
     */
    public $strict = true;

    /**
     * @var array
     */
    public $validList;

    /**
     * getRequiredOptions
     * @return array
     */
    public function getRequiredOptions()
    {
        return ['validList'];
    }
}