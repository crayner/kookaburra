<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/10/2019
 * Time: 08:05
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class RoleList
 * @package App\Validator\SystemAdmin
 * @Annotation
 */
class RoleList extends Constraint
{
    public $message = '{value} is not a valid Role ID';
    public $fieldName = 'id';
    public $propertyPath = null;
}