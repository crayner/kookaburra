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
 * Date: 8/08/2019
 * Time: 12:28
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class RegistrationMinimumAge
 * @package App\Validator
 */
class RegistrationMinimumAge extends Constraint
{
    public $message = 'Your request failed because you do not meet the minimum age for joining this site ({oneString} years of age).';
}