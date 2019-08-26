<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 26/08/2019
 * Time: 09:16
 */

namespace App\Validator;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class URLOrFileValidator
 * @package App\Validator
 */
class URLOrFileValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value)
            return;

        if (is_string($value))
        {
            $urlValidator = new Url();
            $this->context->getValidator()->validate($value, $urlValidator);
            return;
        }

        if ($value instanceof File) {
            $fileValidator = new \Symfony\Component\Validator\Constraints\File();
            $this->context->getValidator()->validate($value, $fileValidator);
            return;
        }
        dd($value);
    }

}