<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/09/2019
 * Time: 13:20
 */

namespace App\Manager\Entity\SystemAdmin;

use App\Provider\ProviderFactory;
use App\Util\TranslationsHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImportReportField
 * @package App\Manager\Entity\SystemAdmin
 */
class ImportReportField
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $desc;

    /**
     * @var array
     */
    private $args;

    /**
     * @var array
     */
    private $relationship = [];

    /**
     * @var string
     */
    private $select;

    /**
     * @var ArrayCollection
     */
    private $relationalEntities;

    /**
     * ImportReportField constructor.
     */
    public function __construct(string $name, array $details)
    {
        $this->setName($name);
        $resolver = new OptionsResolver();
        $resolver->setRequired(['label', 'args', 'select']);
        $resolver->setDefaults(
            [
                'desc' => '',
                'relationship' => [],
            ]
        );
        $details = $resolver->resolve($details);

        foreach($details as $name=>$value)
        {
            $name = 'set' . ucfirst($name);
            $this->$name($value);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Name.
     *
     * @param string $name
     * @return ImportReportField
     */
    public function setName(string $name): ImportReportField
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Label.
     *
     * @param string $label
     * @return ImportReportField
     */
    public function setLabel(string $label): ImportReportField
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getDesc(): string
    {
        return $this->desc;
    }

    /**
     * Desc.
     *
     * @param string $desc
     * @return ImportReportField
     */
    public function setDesc(string $desc): ImportReportField
    {
        $this->desc = $desc;
        return $this;
    }

    /**
     * getArg
     * @param string $name
     * @return mixed
     */
    public function getArg(string $name)
    {
        return $this->getArgs()[$name];
    }

    /**
     * setArg
     * @param string $name
     * @param $value
     * @return ImportReportField
     */
    private function setArg(string $name, $value): ImportReportField
    {
        $this->getArgs()[$name] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getArgs(): array
    {
        return $this->args;
    }

    /**
     * Args.
     *
     * @param array $args
     * @return ImportReportField
     */
    public function setArgs(array $args): ImportReportField
    {
        $resolver = new OptionsResolver();

        $resolver-> setDefaults(
            [
                'filter' => 'string',
                'required' => false,
                'custom' => false,
                'linked' => false,
                'hidden' => false,
                'kind' => '',
                'length' => false,
                'scale' => 0,
                'elements' => [],
                'desc' => '',
                "columnName" => '',
                "fieldName" => '',
                "nullable" => false,
                "precision" => 0,
                "type" => 'string',
                "unique" => false,
                'columnDefinition' => '',
                'function' => false,
                'options' => [],
                'readonly' => false,
            ]
        );
        $resolver->setAllowedValues('filter', ['string','numeric','schoolyear','html','yesno','yearlist']);

        $this->args = $resolver->resolve($args);
        return $this;
    }

    /**
     * @return bool|array
     */
    public function getRelationship()
    {
        return $this->relationship;
    }

    /**
     * Relationship.
     *
     * @param array $relationship
     * @return ImportReportField
     */
    public function setRelationship(array $relationship): ImportReportField
    {
        if ($relationship === []) {
            $this->relationship = $relationship;
            return $this;
        }

        $resolver = new OptionsResolver();
        $resolver->setRequired(['table', 'key', 'field']);

        $this->relationship = $resolver->resolve($relationship);
        return $this;
    }

    /**
     * @return string
     */
    public function getSelect(): string
    {
        return $this->select;
    }

    /**
     * Select.
     *
     * @param string $select
     * @return ImportReportField
     */
    public function setSelect(string $select): ImportReportField
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Is Field Hidden
     *
     * @param string  Field name
     * @return  bool true if marked as a hidden field (or is linked)
     */
    public function isFieldHidden(): bool
    {
        if ($this->isFieldLinked()) {
            return true;
        }

        return $this->getArgs()['hidden'];
    }

    /**
     * Is Field Linked to another field (for relational reference)
     *
     * @param string  Field name
     * @return  bool true if marked as a linked field
     */
    public function isFieldLinked(): bool
    {
        return $this->getArgs()['linked'];
    }

    /**
     * isRequired
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->getArg('required');
    }

    /**
     * Create a human friendly representation of the field value type
     *
     * @param string  Field name
     * @return  array
     */
    public function readableFieldType(): array
    {
        $filter = $this->getArg('filter');
        $kind = $this->getArg('kind');
        $length = $this->getArg('length');

        if ($kind === '') {
            $this->setValueTypeByFilter();
            $kind = $this->getArg('kind');
            $length = $this->getArg('length');
        }

        if ($filter === 'string' && intval($length) > 0)
            $kind = 'char';

        if ($this->isRelational()) {
            extract($this->getRelationship());
            $field = is_array($field) ? current($field) : $field;

            return [
                'prompt' => 'Text',
                'title' => 'Each {name} value should match an existing {field} in table {table}.',
                'titleParams' => ['{name}' => TranslationsHelper::translate($this->getLabel()), '{field}' => $field, '{table}' => !empty($join) ? $join : $table,],
                'extra' => $field,
            ];
        }

        switch ($filter) {
            case 'email':
                return __('Email ({number} chars)', ['number' => $length]);
            case 'url':
                return __('URL ({number} chars)', ['number' => $length]);
            case 'numeric':
                return ['prompt' =>'Number'];
            case 'yesno':
                return [
                    'prompt' => 'Y or N',
                ];
            case 'html':
                return [
                    'prompt' => 'HTML Description',
                    'title' => 'Safe HTML usage is defined as System/AllowableHTML in the Settings.',
                ];
            case 'yearlist':
                return [
                    'prompt' => 'Year List',
                    'title' => 'Comma separated list of Year Group ID',
                ];
        }

        if ($kind === '')
            dump($filter,$this);
        switch ($kind) {
            case 'char':
                return [
                    'prompt' => 'Text ({number} chars)',
                    'promptParams' => ['{number}' => $length],
                ];

            case 'text':
                return $filter != 'string' ? __('Text') . ' (' . $filter . ')' : __('Text');

            case 'integer':
                return __('Number ({number} digits)', ['number' => $length]);

            case 'decimal':
                $scale = $this->getArg('scale');
                $format = str_repeat('0', $length) . "." . str_repeat('0', $scale);
                return __('Decimal ({number} format)', ['number' => $format]);

            case 'date':
                return __('Date');

            case 'boolean':
                return __('True or False');

            case 'enum':
                $options = implode('<br/>', $this->getArg('elements'));
                return '<attr data-title="' . $options . '" style="text-decoration: underline;">' . __('Options') . '</attr>';

            default:
                return [
                    'prompt' => $filter . ' filter not defined.',
                ];
        }

        return [
            'prompt' => 'Abandon Hope'
        ];
    }

    /**
     * setValueTypeByFilter
     * @param $fieldName
     */
    protected function setValueTypeByFilter()
    {
        $type = '';
        $kind = '';

        switch ($this->getArg( 'filter')) {
            case 'string':
                $type = 'text';
                $kind = 'text';
                break;
            case 'date':
                $type = 'date';
                $kind = 'date';
                break;
            case 'url':
                $type = 'url';
                $kind = 'text';
                break;
            case 'email':
                $type = 'email';
                $kind = 'text';
                break;
        }

        $this->setArg('type', $type);
        $this->setArg('kind', $kind);
    }

    /**
     * Is Field Relational
     *
     * @param string  Field name
     * @return  bool true if marked as a required field
     */
    public function isRelational(): bool
    {
        return count($this->getRelationship()) > 0 ? true : false;
    }

    /**
     * isHidden
     * @return bool
     */
    public function isHidden(): bool
    {
        return (bool) $this->getArg('hidden');
    }

    /**
     * isFieldReadOnly
     * @return bool
     */
    public function isFieldReadOnly(): bool
    {
        return (bool) $this->getArg('readonly');
    }

    /**
     * getValue
     * @param $value
     * @return mixed
     */
    public function getValue($value)
    {
        if ($this->isRelational()) {
            extract($this->getRelationship());
            $search = [$field => $value];
            $table = '\App\Entity\\'.$table;
            $entity = $this->getRelationalEntity($table, $field, $value) ?: ProviderFactory::getRepository($table)->findOneBy($search);
            $this->addRelationalEntity($table, $field, $value, $entity);
            return $entity;
        }
        return $value;
    }

    /**
     * getRelationalEntities
     * @return ArrayCollection
     */
    public function getRelationalEntities(): ArrayCollection
    {
        return $this->relationalEntities = $this->relationalEntities ?: new ArrayCollection();
    }

    /**
     * RelationalEntities.
     *
     * @param ArrayCollection $relationalEntities
     * @return ImportReportField
     */
    public function setRelationalEntities(ArrayCollection $relationalEntities): ImportReportField
    {
        $this->relationalEntities = $relationalEntities;
        return $this;
    }

    /**
     * addRelationalEntity
     * @param string $table
     * @param string $field
     * @param string $value
     * @param $entity
     * @return ImportReportField
     */
    public function addRelationalEntity(string $table, string $field, string $value, $entity): ImportReportField
    {
        $fieldCollection = $this->getRelationalEntities()->get($table) ?: new ArrayCollection();
        $valueCollection = $fieldCollection->get($field) ?: new ArrayCollection();
        $valueCollection->set($value, $entity);
        $fieldCollection->set($field, $valueCollection);
        $this->getRelationalEntities()->set($table, $fieldCollection);
        return $this;
    }

    /**
     * Relational.
     *
     * @param ArrayCollection $relational
     * @return ImportReportField
     */
    public function getRelationalEntity(string $table, string $field, string $value)
    {
        $fieldCollection = $this->getRelationalEntities()->get($table) ?: new ArrayCollection();
        $valueCollection = $fieldCollection->get($field) ?: new ArrayCollection();
        return $valueCollection->get($value);
    }
}