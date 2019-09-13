<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 12/09/2019
 * Time: 13:45
 */

namespace App\Form\Transform;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class IntegerToStringTransformer
 * @package App\Form\Transform
 */
class IntegerToStringTransformer implements DataTransformerInterface
{
    /**
     * transform
     * @param mixed $value
     * @return mixed|string
     */
    public function transform($value)
    {
        dump($value);
        if (null === $value)
            return $value;
        return strval($value);
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return int|mixed
     */
    public function reverseTransform($value)
    {
        dump($value);
        if (null === $value)
            return $value;
        return intval($value);
    }

}