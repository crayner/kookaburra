<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 26/08/2019
 * Time: 11:25
 */

namespace App\Form\Transform;


use App\Util\UserHelper;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileURLToStringTransformer
 * @package App\Form\Transform
 */
class FileURLToStringTransformer implements DataTransformerInterface
{
    /**
     * @var string
     */
    private $filePrefix;

    /**
     * FileURLToStringTransformer constructor.
     * @param string $filePrefix
     */
    public function __construct(string $filePrefix)
    {
        $this->filePrefix = $filePrefix;
    }

    /**
     * transform
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        if (null === $value)
            return $value;
        if (is_string($value))
            return $value;
        dd($value);
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        if (null === $value)
            return $value;
        if (is_string($value))
            return $value;

        if ($value instanceof File)
        {
            $user = UserHelper::getCurrentUser();
            $path = realpath($value->getPath());
            $name = substr(trim($this->filePrefix, '_') . '_' . ($user ? $user->getId() : '0') . '_' . date('Ymd') . '_' . uniqid('', true), 0, 32) . '.' . $value->getExtension();

            $name = $value->move($path, $name);
            return $name->getRealPath();
        }

        dd($value);
    }

}