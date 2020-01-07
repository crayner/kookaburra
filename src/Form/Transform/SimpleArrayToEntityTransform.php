<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 6/01/2020
 * Time: 18:48
 */

namespace App\Form\Transform;

use App\Provider\ProviderFactory;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class SimpleArrayToEntityTransform
 * @package App\Form\Transform
 */
class SimpleArrayToEntityTransform implements DataTransformerInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * SimpleArrayToEntitySubcriber constructor.
     * @param array|null $options
     */
    public function __construct(?array $options = null)
    {
        $this->options = $options;
    }

    /**
     * transform
     * @param mixed $value
     * @return mixed|void
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        return $value;
    }

}