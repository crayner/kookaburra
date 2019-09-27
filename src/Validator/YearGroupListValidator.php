<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/09/2019
 * Time: 11:45
 */

namespace App\Validator;


use App\Entity\YearGroup;
use App\Provider\ProviderFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class YearGroupListValidator
 * @package App\Validator
 */
class YearGroupListValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ('' === $value || null === $value)
            return;

        foreach(explode(',', $value) as $id)
        {
            $id = intval(trim($id));
            $yearGroup = ProviderFactory::getRepository(YearGroup::class)->find($id);
            if (!$yearGroup instanceof YearGroup)
            {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{value}', $id)
                    ->setTranslationDomain('messages')
                    ->addViolation();
            }
        }
    }

}