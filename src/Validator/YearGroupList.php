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
    public $fieldName = 'id';
    public $propertyPath = null;
}