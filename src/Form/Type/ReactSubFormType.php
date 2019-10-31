<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/10/2019
 * Time: 11:55
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * Class ReactSubFormType
 * @package App\Form\Type
 */
class ReactSubFormType extends AbstractType
{
    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'react_sub_form';
    }
}