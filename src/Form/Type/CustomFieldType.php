<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/08/2019
 * Time: 16:54
 */

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class CustomFieldType
 * @package App\Form
 */
class CustomFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $name = 'value';
        $fieldID = trim($options['property_path'], '[]');
        $field = null;
        foreach($options['customFields'] as $field)
            if ($fieldID === $field->getId())
                break;

        switch($field->getType()) {
            case 'date':
                $builder->add($name, DateType::class,
                    [
                        'required' => $field->getRequired() === 'Y' ? true : false,
                        'mapped' => false,
                        'label' => $field->getName(),
                        'attr' => [
                            'class' => 'w-full',
                        ],
                        'help' => $field->getDescription(),
                        'translation_domain' => false,
                        'constraints' => $field->getRequired() === 'Y' ? [
                            new NotBlank(),
                        ] : [],
                    ]
                );
                break;
            case 'url':
                $builder->add($name, UrlType::class,
                    [
                        'required' => $field->getRequired() === 'Y' ? true : false,
                        'mapped' => false,
                        'label' => $field->getName(),
                        'attr' => [
                            'class' => 'w-full',
                        ],
                        'help' => $field->getDescription(),
                        'constraints' => $field->getRequired() === 'Y' ? [
                            new NotBlank(),
                        ] : [],
                        'translation_domain' => false,
                    ]
                );
                break;
            case 'select':
                $builder->add($name, ChoiceType::class,
                    [
                        'choices' => $this->fromString($field->getOptions()),
                        'placeholder' => 'Please select...',
                        'label' => $field->getName(),
                        'required' => $field->getRequired() === 'Y' ? true : false,
                        'mapped' => false,
                        'attr' => [
                            'class' => 'w-full',
                        ],
                        'help' => $field->getDescription(),
                        'constraints' => $field->getRequired() === 'Y' ? [
                            new NotBlank(),
                        ] : [],
                        'translation_domain' => false,
                    ]
                );
                break;
            case 'text':
                $builder->add($name, TextareaType::class,
                    [
                        'label' => $field->getName(),
                        'attr' => [
                            'rows' => intval($field->getOptions()) > 0 ? intval($field->getOptions()) : 6,
                            'class' => 'w-full',
                        ],
                        'required' => $field->getRequired() === 'Y' ? true : false,
                        'mapped' => false,
                        'help' => $field->getDescription(),
                        'constraints' => $field->getRequired() === 'Y' ? [
                            new NotBlank(),
                        ] : [],
                        'translation_domain' => false,
                    ]
                );
                break;
            case 'varchar':
                $builder->add($name, TextType::class,
                    [
                        'attr' => [
                            'maxLength' => intval($field->getOptions()) > 0 ? intval($field->getOptions()) : 255,
                            'class' => 'w-full',
                        ],
                        'label' => $field->getName(),
                        'required' => $field->getRequired() === 'Y' ? true : false,
                        'mapped' => false,
                        'help' => $field->getDescription(),
                        'constraints' => $field->getRequired() === 'Y' ? [
                            new NotBlank(),
                        ] : [],
                        'translation_domain' => false,
                    ]
                );
                break;
            default:
                throw new \InvalidArgumentException(sprintf('The "%s" Person Field Type is not valid!', $field->getType()));
        }

        return $builder;
    }


    /**
     * Build an internal options array from a provided CSV string.
     * @param   string  $value
     * @return  self
     */
    public function fromString($value): array
    {
        if (null === $value || '' === $value) {
            return [];
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Element %s: fromString expects value to be a string, %s given.', $this->personField->getName(), gettype($value)));
        }

        if (!empty($value)) {
            $pieces = str_getcsv($value);

            foreach ($pieces as $piece) {
                $piece = trim($piece);
                $values[] = $piece;
            }
        }

        return $values;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'translation_domain' => false,
                'data_class' => null,
                'customFields' => [],
            ]
        );
    }
}