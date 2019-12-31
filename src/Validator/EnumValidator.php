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
 * Date: 25/07/2019
 * Time: 13:30
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EnumValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $validList = $constraint->validList;
        if (!$constraint->strict)
        {
            $validList[] = '';
            $validList[] = null;
        }
        if (! in_array($value, $validList))
            $this->context->buildViolation('The value "{value}" is not valid. Valid results are "[{valid}]"')
                ->setParameter('%(value}', $value)
                ->setParameter('{valid}', implode('","', $constraint->validList))
                ->setTranslationDomain('kookaburra')
                ->addViolation();
    }
}