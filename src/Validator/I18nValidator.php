<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/07/2019
 * Time: 10:59
 */

namespace App\Validator;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class I18nValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $valid = \App\Entity\I18n::getLanguages();

        if (!isset($valid[$value]))
            $this->context->buildViolation('The language %{code} selected is not a valid language choice for Kookaburra. Valid choices are %{codes}')
                ->setParameter('%{code}', $value)
                ->setParameter('%{codes}', implode(', ', $valid))
                ->setTranslationDomain('kookaburra')
                ->addViolation();
    }
}