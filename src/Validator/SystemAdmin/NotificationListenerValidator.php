<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 11/09/2019
 * Time: 11:45
 */

namespace App\Validator\SystemAdmin;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class NotificationListenerValidator
 * @package App\Validator\SystemAdmin
 */
class NotificationListenerValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        dump($value, $this->context->getObject());

        $this->context->buildViolation('This will aways be wrong until you get off you bum!')
            ->atPath('[person]')
            ->setTranslationDomain('messages')
            ->addViolation();
    }

}