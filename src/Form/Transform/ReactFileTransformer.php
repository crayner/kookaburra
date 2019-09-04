<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 4/09/2019
 * Time: 13:14
 */

namespace App\Form\Transform;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ReactFileTransformer
 * @package App\Form\Transform
 */
class ReactFileTransformer implements DataTransformerInterface
{
    /**
     * transform
     * @param mixed $value
     * @return mixed|void
     */
    public function transform($value)
    {
        if ($value === null)
            return null;

        if (is_string($value)) {
            $public = realpath(__DIR__ . '/../../../public');
            $value = realpath($value) ?: (realpath($public.$value) ?: '');
            if ('' === $value)
                return null;
            $value = new File($value, true);
            return $value;
        }

        return $value;
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        if (is_string($value)) {
            $public = realpath(__DIR__ . '/../../../public');
            $value = realpath($value) ?: (realpath($public.$value) ?: '');
            if ('' === $value)
                return null;

            return $value;
        }

        return $value;
    }
}