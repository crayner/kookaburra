<?php
namespace App\Form\Transform;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class FileToStringTransformer
 * @package App\Form\Transform
 */
class FileToStringTransformer implements DataTransformerInterface
{
	/**
	 * Transforms an string to File
	 *
	 * @param  File|null $data
	 *
	 * @return string
	 */
	public function transform($data): File
	{

	    $relative = __DIR__ . '/../../../public';
		$file = is_file($relative.$data) ? realpath($relative.$data) : null;
		$data = new File('', false);
		if ($file = realpath($relative.$data) && is_file($relative.$data))
		    $data = new File($file, true);

        return $data;
	}

	/**
	 * Transforms a File into a string.
	 *
	 * @param mixed $data
	 *
	 * @return null|string
	 * @internal param $ null|File
	 */
	public function reverseTransform($data)
	{
		return $data;
	}
}