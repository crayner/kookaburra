<?php
namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class Colour
 * @package App\Validator
 * @Annotation
 */
class Colour extends Constraint
{
    /**
     * @var string
     */
    public $message = 'colour.validation.error';

    /**
     * @var string
     */
    public $transDomain = 'messages';

    /**
     * @var string
     */
    public $enforceType = 'any';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return ColourValidator::class;
    }
}