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
 * Date: 26/07/2019
 * Time: 09:51
 */

namespace App\Exception;

use Throwable;

class MissingEntityException extends \RuntimeException
{
    /**
     * MissingClassException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     * @param string|null $class
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null, string $class = null) {
        if ('' === $message)
            $message = sprintf('The entity "%s" was not found.', $class);
        parent::__construct($message, $code, $this);
    }
}