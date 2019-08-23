<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/08/2019
 * Time: 14:41
 */

namespace App\Form\Security;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class GeneratePasswordType extends AbstractType
{
    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'generate_password';
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return RepeatedType::class;
    }
}