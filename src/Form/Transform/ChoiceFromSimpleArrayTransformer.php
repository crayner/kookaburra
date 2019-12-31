<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 3/12/2019
 * Time: 14:09
 */

namespace App\Form\Transform;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class ChoiceFromSimpleArrayTransformer
 * @package App\Form\Transform
 */
class ChoiceFromSimpleArrayTransformer implements DataTransformerInterface
{
    /**
     * transform
     * @param mixed $value
     * @return array|mixed
     */
    public function transform($value)
    {
        if (empty($value))
            return [];

        if (is_array($value))
            return $value;
        $result = [];

        foreach(explode(',',$value) as $item)
            $result[trim($item)] = trim($item);

        return $result;
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return mixed|string
     */
    public function reverseTransform($value)
    {
        if (is_array($value))
            $value = implode(',',$value);
        return $value;
    }

}