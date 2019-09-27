<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/09/2019
 * Time: 11:44
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class YearGroupList
 * @package App\Validator
 * @Annotation
 */
class YearGroupList extends Constraint
{
    public $message = '{value} is not a valid Year Group ID';
}