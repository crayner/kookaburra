<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/10/2019
 * Time: 12:33
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ScaleGradeValidator
 * @package App\Validator
 */
class ScaleGradeValidator extends ConstraintValidator
{
    /**
     * validate
     *
     * Check that ONLY one value in a Scale is the default value.
     * Check that each sequence in a Scale is unique.
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        dd($value);

    }

}