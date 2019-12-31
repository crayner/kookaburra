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
 * Date: 30/11/2019
 * Time: 15:14
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class SimpleArray
 * @package App\Validator
 */
class SimpleArray extends Constraint
{
    public $transDomain = 'messages';
}