<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/12/2019
 * Time: 14:22
 */

namespace App\Form\Transform;


use PhpOffice\PhpSpreadsheet\Calculation\DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateSettingTransformer implements DataTransformerInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * DateSettingTransformer constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * transform
     * @param mixed $value
     * @return \DateTimeImmutable|mixed|DateTime|null
     */
    public function transform($value)
    {
        if (empty($value))
            return null;

        try {
            if (strpos($this->options['input'], 'immutable') === false) {
                $value = new \DateTime($value);
            } else {
                $value = new \DateTimeImmutable($value);
            }
        } catch (\Exception $e) {
            throw new TransformationFailedException('The date provided could not be transformed.', 0, $e);
        }

        return $value;
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return mixed|string
     */
    public function reverseTransform($value)
    {
        return $value;
        if (empty($value))
            return null;

        if ($value instanceof DateTime || $value instanceof \DateTimeImmutable)
            return $value->format('Y-m-d');
        return null;
    }

}