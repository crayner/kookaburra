<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/09/2019
 * Time: 14:45
 */

namespace App\Form\Modules\SystemAdmin;

use App\Entity\Action;
use App\Entity\NotificationEvent;
use App\Entity\Person;
use App\Form\Type\HiddenEntityType;
use App\Provider\ProviderFactory;
use App\Validator\SystemAdmin\NotificationListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NotificationListenerType
 * @package App\Form\Modules\SystemAdmin
 */
class NotificationListenerType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $action = ProviderFactory::getRepository(Action::class)->findOneByName($options['event']->getAction()->getName());
        $roles= [];
        foreach($action->getPermissions() as $permission)
            $roles[] = $permission->getRole()->getId();

        $result = ProviderFactory::getRepository(Person::class)->findByRoles($roles);
        $people = [];
        foreach($result as $person) {
            $people[$person['name']][] = $person[0];
        }

        $allScopes = [
            'All'                   => 'All',
            'gibbonPersonIDStudent' => 'Student',
            'gibbonPersonIDStaff'   => 'Staff',
            'gibbonYearGroupID'     => 'Year Group',
        ];
        $eventScopes = array_combine(explode(',', $options['event']->getScopes()), explode(',', trim($options['event']->getScopes())));
        $availableScopes = array_intersect_key($allScopes, $eventScopes);
        $builder
            ->add('person', EntityType::class,
                [
                    'class' => Person::class,
                    'choice_label' => 'fullName',
                    'label' => 'Name',
                    'help' => 'Available only to users with the required permission.',
                    'choices' => $people,
                    'placeholder' => 'Please Select...',
                ]
            )

            ->add('scope', ChoiceType::class,
                [
                    'label' => 'Scope',
                    'choices' => array_flip($availableScopes),
                    'on_change' => 'toggleScopeType',
                ]
            )
            ->add('scopeTypeChoice', ChoiceType::class,
                [
                    'label' => 'Scope Type Choices',
                    'choices' => [],
                    'placeholder' => 'Please Select...',
                ]
            )
            ->add('event', HiddenEntityType::class,
                [
                    'class' => NotificationEvent::class,
                    'row_style' => 'hidden',
                ]
            )
        ;
        $builder->addViewTransformer($this);
    }

    /**
     * configureOptions
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'event',
            ]
        );
        $resolver->setDefaults(
            [
                'constraints' => [
                    new NotificationListener(),
                ],
            ]
        );
    }

    /**
     * transform
     * @param mixed $value
     * @return mixed|void
     */
    public function transform($value)
    {
    }

    /**
     * reverseTransform
     * @param mixed $value
     * @return mixed|void
     */
    public function reverseTransform($value)
    {
        if (is_array($value)) {
            $nl = new \App\Entity\NotificationListener();
            $nl->setPerson(ProviderFactory::getRepository(Person::class)->find($value['person'] ?: 0));
            $nl->setScopeType($value['scope'] ?: 'All');
            $nl->setScopeID(intval($value['scopeTypeChoice']) !== 0 ?: null );
            $nl->setEvent($value['event']);
            return $nl;
        }
    }
}