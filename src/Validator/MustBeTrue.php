<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 16/12/2019
 * Time: 10:47
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class MustBeTrue
 * @package App\Validator
 */
class MustBeTrue extends Constraint
{
    public $message = 'The question must be acknowledged';

    public $validTrue = ['Y','YES','TRUE',true];

    public $translationDomain = 'messages';
}