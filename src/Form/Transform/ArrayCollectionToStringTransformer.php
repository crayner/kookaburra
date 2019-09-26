<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 26/09/2019
 * Time: 07:17
 */

namespace App\Form\Transform;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ArrayCollectionToStringTransformer implements DataTransformerInterface
{
    /**
     * @var Serializer
     */
    private $serialiser;

    /**
     * @var array
     */
    private $context = [];

    /**
     * @var string
     */
    private $object;

    /**
     * ArrayCollectionToStringTransformer constructor.
     * @param array $context
     */
    public function __construct(string $object, array $context = [])
    {
        $this->context = $context;
        $this->object = $object;
    }

    /**
     * @return Serializer
     */
    public function getSerialiser(): Serializer
    {
        return $this->serialiser = $this->serialiser ?: new Serializer([new ArrayDenormalizer(), new ObjectNormalizer(null, null, null, new ReflectionExtractor())], [new JsonEncoder(), new YamlEncoder()]);
    }

    /**
     * transform
     * @param mixed $value
     * @return bool|float|int|mixed|string
     */
    public function transform($value)
    {
        if (null === $value || '' === $value)
            return $value;
        if ($value instanceof ArrayCollection)
        {
            $value = $this->getSerialiser()->serialize($value,'json');
        }

        return $value;
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return array|mixed|object
     */
    public function reverseTransform($value)
    {
        if (null === $value || '' === $value)
            return $value;

        if (is_string($value)) {
            $value = $this->getSerialiser()->deserialize($value, $this->object . '[]', 'json');
            $value = new ArrayCollection($value ?: []);
        }

        return $value;
    }

}