<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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