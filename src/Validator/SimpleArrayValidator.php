<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 30/11/2019
 * Time: 15:14
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class SimpleArrayValidator
 * @package App\Validator
 */
class SimpleArrayValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     * @return mixed|string|void
     */
    public function validate($value, Constraint $constraint)
    {
        if (in_array($value, ['', null]))
            return ;

        try {
            $list = explode(',', $value);
            foreach($list as $q=>$w)
                $list[$q] = trim($w);
            $value = implode(',', $list);
            return $value;
        } catch (\Exception $e) {
            $this->context->buildViolation('The list is not correctly formatted.')
                ->setTranslationDomain($constraint->transDomain)
                ->addViolation();
        }
    }

}