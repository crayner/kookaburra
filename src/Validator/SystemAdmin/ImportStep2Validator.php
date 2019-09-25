<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 22/09/2019
 * Time: 11:25
 */

namespace App\Validator\SystemAdmin;

use App\Form\Entity\ImportControl;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class ImportStep2Validator
 * @package App\Validator
 */
class ImportStep2Validator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof ImportControl)
            $this->context->buildViolation($constraint->message)
                ->addViolation();

        if (in_array($value->getMode(), ['sync', 'update']) && ($value->isSyncField() && $value->getSyncColumn() < 0))
            $this->context->buildViolation($constraint->message)
                ->atPath('syncColumn')
                ->addViolation();
    }

}