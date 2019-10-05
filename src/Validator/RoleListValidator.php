<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/10/2019
 * Time: 08:06
 */

namespace App\Validator;

use App\Entity\Role;
use App\Provider\ProviderFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class RoleListValidator
 * @package App\Validator
 */
class RoleListValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ('' === $value || null === $value || [] === $value)
            return;

        if (!is_array($value))
            $value = explode(',', $value);
        while (isset($value[0]) && $value[0] === '')
            unset($value[0]);

        foreach($value as $id)
        {
            if (intval(trim($id)) > 0)
                $id = intval(trim($id));
            $yearGroup = ProviderFactory::getRepository(Role::class)->findOneBy([$constraint->fieldName => $id]);
            if (!$yearGroup instanceof Role)
            {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{value}', $id)
                    ->atPath($constraint->propertyPath)
                    ->setTranslationDomain('messages')
                    ->addViolation();
            }
        }
    }
}