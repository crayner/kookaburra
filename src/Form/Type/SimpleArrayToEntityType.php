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
 * Time: 17:53
 */

namespace App\Form\Type;

use App\Form\EventSubscriber\SimpleArrayToEntitySubcriber;
use App\Form\Transform\SimpleArrayToEntityTransform;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class SimpleArrayToEntityType
 * @package App\Form\Type
 */
class SimpleArrayToEntityType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventSubscriber(new SimpleArrayToEntitySubcriber($options));
    }

    /**
     * getParent
     * @return string|null
     */
    public function getParent()
    {
        return EntityType::class;
    }
}