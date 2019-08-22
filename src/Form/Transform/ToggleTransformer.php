<?php
namespace App\Form\Transform;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ToggleTransformer implements DataTransformerInterface
{
	/**
	 * Transforms an string to boolean
	 *
	 * @param   $data
	 *
	 * @return bool
	 */
	public function transform($data): bool
	{
        if (in_array($data, ['Y','1',true]))
            return true;
        return false;
	}

	/**
	 * Transforms a string to boolean
	 *
	 * @param mixed $data
	 *
	 * @return string
	 * @internal param $ null|File
	 */
	public function reverseTransform($data): string
	{
        if (in_array($data, ['1', 'Y', true]))
            return 'Y';
        return 'N';
	}
}