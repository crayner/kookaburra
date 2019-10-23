<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 21/10/2019
 * Time: 10:54
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Class AutoSuggestType
 * @package App\Form\Type
 */
class AutoSuggestType extends AbstractType
{
    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * getBlockPrefix
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'auto_suggest';
    }
}