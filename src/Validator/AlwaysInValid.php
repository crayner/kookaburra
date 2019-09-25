<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class AlwaysInValid
 * @package Hillrange\Form\Validator
 * @Annotation
 */
class AlwaysInValid extends Constraint
{
    /**
     * @var string
     */
    public $message = 'This field %{name} is always invalid';

    /**
     * validatedBy
     * @return string
     */
    public function validatedBy()
    {
        return AlwaysInValidValidator::class;
    }
}