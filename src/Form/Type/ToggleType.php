<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class ToggleType extends AbstractType
{
    /**
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return 'toggle';
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return HiddenType::class;
    }
}