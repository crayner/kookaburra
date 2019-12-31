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
 * Date: 26/07/2019
 * Time: 09:37
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class DirectoryValidator
 * @package App\Validator
 */
class DirectoryValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!is_dir($value))
            $this->context->buildViolation('The directory "{directory}" does not exist.')
                ->setParameter('{directory}', $value)
                ->setTranslationDomain('kookaburra')
                ->addViolation();
    }
}