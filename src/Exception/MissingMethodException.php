<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 26/07/2019
 * Time: 09:51
 */

namespace App\Exception;

use Throwable;

class MissingMethodException extends \Exception
{
    /**
     * MissingClassException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string|null $class
     */
    public function __construct(Object $object, string $method)
    {
        if (strpos($method, 'is') === 0)
            $method = substr($method, 2);
        if (strpos($method, 'get') === 0)
            $method = substr($method, 3);
        $message = sprintf('The method "is%s" or "get%s" was not found in the class "%s"', $method,  $method, get_class($object));
        parent::__construct($message);
    }
}