<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 16/09/2019
 * Time: 12:21
 */

namespace App\Manager\Entity;


use App\Entity\Action;
use App\Entity\Country;
use App\Entity\Language;
use App\Entity\Module;
use App\Entity\PersonField;
use App\Entity\YearGroup;
use App\Exception\MissingClassException;
use App\Provider\ProviderFactory;
use App\Util\SecurityHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportReport
{
    /**
     * Information about the overall Import Type
     */
    protected $details = [];

    /**
     * Permission information for user access
     */
    protected $access = [];

    /**
     * Values that can be used for sync & updates
     */
    protected $primaryKey;
    protected $uniqueKeys = [];
    protected $keyFields = [];
    protected $fields = [];

    /**
     * Holds the table fields and information for each field
     */
    protected $table = [];
    protected $tables = [];
    protected $tablesUsed = [];

    /**
     * Has the structure been checked against the database?
     */
    protected $validated = false;

    /**
     * Relational data: System-wide (for filters)
     * @var array
     */
    protected $useYearGroups = false;
    protected $yearGroups = [];

    protected $useLanguages = false;
    protected $languages = [];

    protected $useCountries = false;
    protected $countries = [];

    protected $usePhoneCodes = false;
    protected $phoneCodes = [];

    protected $useCustomFields = false;
    protected $customFields = [];

    protected $useSerializedFields = false;

    private $join = [];

    /**
     * ImportReport constructor.
     * @param array $data
     * @param bool $validateStructure
     */
    public function __construct(array $data, bool $validateStructure = true)
    {
        if (isset($data['details'])) {
            $this->details = $data['details'];
        }

        if (isset($data['access'])) {
            $this->access = $data['access'];
        }

        if (isset($data['primaryKey'])) {
            $this->primaryKey = $data['primaryKey'];
        }

        if (isset($data['join'])) {
            $this->setJoin($data['join']);
        }

        if (isset($data['uniqueKeys'])) {
            $this->uniqueKeys = $data['uniqueKeys'];

            //Grab the unique fields used in all keys
            foreach ($this->uniqueKeys as $key) {
                if (is_array($key) && count($key) > 1) {
                    $this->keyFields = array_merge($this->keyFields, $key);
                } else {
                    $this->keyFields[] = $key;
                }
            }

            $this->keyFields = array_unique(array_reduce($this->uniqueKeys, function ($group, $item) {
                $keys = is_array($item) ? $item : [$item];
                return array_merge($group, $keys);
            }, []));
        }

        if (isset($data['tables']) && is_array($data['tables'])) {
            // Handle multiple tables in one file
            $this->fields = $data['fields'];
            $this->tables = $data['tables'];
        } elseif (isset($data['table'])) {
            // Convert single table into an array
            $this->fields = $data['table'];
            $this->tables[$this->details['table']] = [
                'primaryKey' => $data['primaryKey'] ?? '',
                'uniqueKeys' => $data['uniqueKeys'] ?? [],
                'fields' => array_keys($data['table']) ?? [],
            ];
        }

        if (!empty($this->tables)) {

            foreach ($this->tables as $tableName => $table) {
                $this->tablesUsed[] = $tableName;

                $this->switchTable($tableName);

                // Add relational tables to the tablesUsed array so they're locked
                foreach ($this->table as $fieldName => $field) {
                    if ($this->isFieldRelational($fieldName)) {
                        $relationship = $this->getField($fieldName, 'relationship');
                        if (!in_array($relationship['table'], $this->tablesUsed)) {
                            $this->tablesUsed[] = $relationship['table'];
                        }
                    }

                    // Check the filters so we know if extra data is nessesary
                    $filter = $this->getField($fieldName, 'filter');
                    if ($filter == 'yearlist') {
                        $this->useYearGroups = true;
                    }
                    if ($filter == 'language') {
                        $this->useLanguages = true;
                    }
                    if ($filter == 'country') {
                        $this->useCountries = true;
                    }
                    if ($filter == 'phonecode') {
                        $this->usePhoneCodes = true;
                    }
                    if ($filter == 'customfield') {
                        $this->useCustomFields = true;
                    }

                    if (!empty($this->getField($fieldName, 'serialize'))) {
                        $this->useSerializedFields = true;
                    }
                }
            }

            $this->tablesUsed = array_unique($this->tablesUsed);
        }

        foreach ($this->tables as $tableName => $table) {
            $this->switchTable($tableName);
            $this->validated = true;

            if ($validateStructure == true) {
                $this->validated &= $this->validateWithDatabase();
                $this->loadRelationalData();
            } else {
                $this->validated &= $this->validateWithDatabase();
            }
        }

        $this->loadAccessData();

        if (empty($this->tables) || empty($this->details)) {
            return null;
        }
    }

    /**
     * Switch the active table - one table is handled at a time.
     *
     * @param string $tableName
     */
    public function switchTable($tableName)
    {
        /**
        if (isset($this->tables[$tableName])) {
            // Intersect only the fields relative to this table
            $fields = array_flip($this->tables[$tableName]['fields']);
            $this->table = array_intersect_key($this->fields, $fields);

            $this->primaryKey = $this->tables[$tableName]['primaryKey'] ?? '';
            $this->uniqueKeys = $this->tables[$tableName]['uniqueKeys'] ?? [];
            $this->details['table'] = $tableName;

            $this->keyFields = array_unique(array_reduce($this->uniqueKeys, function ($group, $item) {
                $keys = is_array($item) ? $item : [$item];
                return array_merge($group, $keys);
            }, []));
        }
        **/
    }

    /**
     * Is Field Relational
     *
     * @param string  Field name
     * @return  bool true if marked as a required field
     */
    public function isFieldRelational($fieldName)
    {
        return (isset($this->fields[$fieldName]['relationship']) && !empty($this->fields[$fieldName]['relationship']));
    }

    /**
     * Get Field Information by Key
     *
     * @param string  Field Name
     * @param string  Key to retrieve
     * @param string  Default value to return if key doesn't exist
     * @return  mixed
     */
    public function getField($fieldName, $key, $default = "")
    {
        if (isset($this->fields[$fieldName][$key])) {
            return $this->fields[$fieldName][$key];
        } elseif (isset($this->fields[$fieldName]['args'][$key])) {
            return $this->fields[$fieldName]['args'][$key];
        } else {
            return $default;
        }
    }

    /**
     * Compares the ImportReport structure with the database table to ensure imports will succeed
     *
     * @return  bool    true if all fields match existing table columns
     */
    protected function validateWithDatabase()
    {
        $table = $this->getDetail('table');
        $className = '\App\Entity\\' . $table;

        if (!class_exists('\App\Entity\\' . $table))
            return false;

        $em = ProviderFactory::getEntityManager();
        $metaData = $em->getClassMetadata($className);

        $validatedFields = 0;
        foreach ($this->table as $fieldName => $field) {
            if ($this->isFieldReadOnly($fieldName)) {
                $this->setValueTypeByFilter($fieldName);
                $validatedFields++;
                continue;
            }

            $columnFieldName = lcfirst(stripos($fieldName, '.') !== false ? trim(strrchr($fieldName, '.'), '.') : $fieldName);

            if (in_array($columnFieldName, $metaData->getAssociationNames())) {
                $validatedFields++;
            } elseif (in_array($columnFieldName, $metaData->getFieldNames())) {
                $validatedFields++;
            } else {
                dd($fieldName, $field, $columnFieldName, $metaData);
                echo '<div class="error">Invalid field ' . $fieldName . '</div>';
            }
        }

        return ($validatedFields == count($this->table));
    }

    /**
     * Get Detail
     *
     * @param string  key - name of the detail to retrieve
     * @param string  default - an optional value to return if key doesn't exist
     * @return  mixed
     */
    public function getDetail($key, $default = "")
    {
        return (isset($this->details[$key])) ? $this->details[$key] : $default;
    }

    /**
     * Is Field Read Only (for relational reference)
     *
     * @param string  Field name
     * @return  bool true if marked as a read only field
     */
    public function isFieldReadOnly($fieldName)
    {
        $readonly = $this->fields[$fieldName]['args']['readonly'] ?? false;
        return is_array($readonly) ? in_array($this->getCurrentTable(), $readonly) : $readonly;
    }

    /**
     * setValueTypeByFilter
     * @param $fieldName
     */
    protected function setValueTypeByFilter($fieldName)
    {
        $type = '';
        $kind = '';

        switch ($this->getField($fieldName, 'filter')) {
            case 'string':
                $type = 'text';
                $kind = 'text';
                break;
            case 'date':
                $type = 'date';
                $kind = 'date';
                break;
            case 'url':
                $type = 'text';
                $kind = 'text';
                break;
            case 'email':
                $type = 'text';
                $kind = 'text';
                break;
        }

        $this->setField($fieldName, 'type', $type);
        $this->setField($fieldName, 'kind', $kind);
    }

    /**
     * Set Field Information by Key
     *
     * @param string  Field Name
     * @param string  Key to retrieve
     * @param string  Value to set
     */
    protected function setField($fieldName, $key, $value)
    {
        if (isset($this->fields[$fieldName])) {
            $this->fields[$fieldName][$key] = $value;
        } else {
            $this->fields[$fieldName] = array($key => $value);
        }
    }

    /**
     * Load Access Data - for user permission checking, and category names
     *
     */
    protected function loadAccessData()
    {
        if (empty($this->access['module']) || empty($this->access['action'])) {
            $this->access['protected'] = false;
            $this->details['category'] = 'Gibbon';
            return;
        }

        $module = $this->getModuleByName($this->access['module']);
        $action = ProviderFactory::getRepository(Action::class)->findOneByNameModule($this->access['action'], $module);

        $this->access['protected'] = true;
        $this->access['entryURL'] = $action->getEntryURL();

        if (empty($this->details['category'])) {
            $this->details['category'] = $action->getCategory();
        }
    }

    /**
     * @var ArrayCollection
     */
    private $modules;

    /**
     * @return ArrayCollection
     */
    public function getModules(): ArrayCollection
    {
        return $this->modules = $this->modules ?: new ArrayCollection();
    }

    /**
     * getModuleByName
     * @param string $name
     */
    public function getModuleByName(string $name): Module
    {
        if ($this->getModules()->containsKey($name))
            return $this->getModules()->get($name);
        $this->modules->set($name, ProviderFactory::getRepository(Module::class)->findOneByName($name));
        return $this->modules->get($name);
    }

    /**
     * isImportAccessible
     * @return bool
     * @throws \Exception
     */
    public function isImportAccessible(): bool
    {
        if (!$this->getAccessDetail('protected'))
            return true;

        if (strpos($this->getAccessDetail('entryURL'), '.php') === false)
            return SecurityHelper::isRouteAccessible(strtolower(str_replace(' ', '_', $this->getAccessDetail('module')) . '__' . $this->getAccessDetail('entryURL')));
        else
            return SecurityHelper::isActionAccessible('/modules/' . $this->getAccessDetail('module') . '/' . $this->getAccessDetail('entryURL'));
    }

    /**
     * Get Access Detail
     *
     * @param string  key - name of the access key to retrieve
     * @param string  default - an optional value to return if key doesn't exist
     * @return  var
     */
    public function getAccessDetail($key, $default = "")
    {
        return (isset($this->access[$key])) ? $this->access[$key] : $default;
    }

    /**
     * Load Relational Data
     */
    protected function loadRelationalData()
    {
        // Grab the year groups so we can translate Year Group Lists without a million queries
        if ($this->useYearGroups) {
            $resultYearGroups = ProviderFactory::getRepository(YearGroup::class)->findBy([], ['sequenceNumber' => 'ASC']);
            foreach ($resultYearGroups as $yearGroup)
                $this->yearGroups[$yearGroup->getNameShort()] = $yearGroup->getId();
        }

        // Grab the Languages for system-wide relational data (filters)
        if ($this->useLanguages) {
            $resultLanguages = ProviderFactory::getRepository(Language::class)->findAll();
            foreach ($resultLanguages as $language)
                $this->languages[$language->getName()] = $language->getName();;
        }

        // Grab the Countries for system-wide relational data (filters)
        if ($this->useCountries || $this->usePhoneCodes) {
            $resultCountries = ProviderFactory::getRepository(Country::class)->findAll();
            foreach ($resultCountries as $country) {
                if ($this->useCountries) {
                    $this->countries[$country->getPrintableName()] = $country->getPrintableName();
                }
                if ($this->usePhoneCodes) {
                    $this->phoneCodes[$country->getIddCountryCode()] = $country->getIddCountryCode();
                }

            }
        }

        // Grab the user-defined Custom Fields
        if ($this->useCustomFields) {
            $resultCustomFields = ProviderFactory::getRepository(PersonField::class)->findByActive('Y');

            foreach ($resultCustomFields as $field)
                $this->customFields[$field->getName()] = $field;

            if (count($resultCustomFields) > 0) {
                foreach ($this->table as $fieldName => $field) {
                    $customFieldName = $this->getField($fieldName, 'name');
                    if (!isset($this->customFields[$customFieldName])) {
                        continue;
                    }

                    $type = $this->customFields[$customFieldName]->getType();
                    if ($type == 'varchar') {
                        $this->setField($fieldName, 'kind', 'char');
                        $this->setField($fieldName, 'type', 'varchar');
                        $this->setField($fieldName, 'length', $this->customFields[$customFieldName]->getOptions());
                    } elseif ($type == 'select') {
                        $this->setField($fieldName, 'kind', 'enum');
                        $this->setField($fieldName, 'type', 'enum');
                        $elements = explode(',', $this->customFields[$customFieldName]->getOptions());
                        $this->setField($fieldName, 'elements', $elements);
                        $this->setField($fieldName, 'length', count($elements));
                    } elseif ($type == 'text' || $type == 'date') {
                        $this->setField($fieldName, 'kind', $type);
                        $this->setField($fieldName, 'type', $type);
                    }

                    $this->setField($fieldName, 'customField', $this->customFields[$customFieldName]->getId());

                    $args = $this->getField($fieldName, 'args');
                    $args['required'] = ($this->customFields[$customFieldName]->isRequired());
                    $this->setField($fieldName, 'args', $args);
                }
            }
        }
    }

    /**
     * Get All Fields used in the import, regardless of table
     *
     * @return  array   2D array of table field names used in this import
     */
    public function getAllFields()
    {
        return (isset($this->fields)) ? array_keys($this->fields) : [];
    }

    /**
     * Is Field Hidden
     *
     * @param string  Field name
     * @return  bool true if marked as a hidden field (or is linked)
     */
    public function isFieldHidden($fieldName)
    {
        if ($this->isFieldLinked($fieldName)) {
            return true;
        }

        $hidden = $this->fields[$fieldName]['args']['hidden'] ?? false;
        return is_array($hidden) ? in_array($this->getCurrentTable(), $hidden) : $hidden;
    }

    /**
     * Is Field Linked to another field (for relational reference)
     *
     * @param string  Field name
     * @return  bool true if marked as a linked field
     */
    public function isFieldLinked($fieldName)
    {
        return (isset($this->fields[$fieldName]['args']['linked'])) ? $this->fields[$fieldName]['args']['linked'] : false;
    }

    /**
     * getCurrentTable
     * @return mixed
     */
    public function getCurrentTable()
    {
        return $this->details['table'];
    }

    /**
     * Is Field Required
     *
     * @param string  Field name
     * @return  bool true if marked as a required field
     */
    public function isFieldRequired($fieldName)
    {
        $required = $this->fields[$fieldName]['args']['required'] ?? false;
        return is_array($required) ? in_array($this->getCurrentTable(), $required) : $required;
    }

    /**
     * Create a human friendly representation of the field value type
     *
     * @param string  Field name
     * @return  string
     */
    public function readableFieldType($fieldName)
    {
        $filter = $this->getField($fieldName, 'filter');
        $kind = $this->getField($fieldName, 'kind');
        $length = $this->getField($fieldName, 'length');

        if ($this->isFieldRelational($fieldName)) {
            extract($this->getField($fieldName, 'relationship'));
            $field = is_array($field) ? current($field) : $field;

            $helpText = __('Each {name} value should match an existing {field} in {table}.', [
                'name' => $this->getField($fieldName, 'name'),
                'field' => $field,
                'table' => !empty($join) ? $join : $table,
            ]);

            return '<abbr title="' . $helpText . '">' . __('Text') . ' (' . $field . ')</abbr>';
        }

        switch ($filter) {
            case 'email':
                return __('Email ({number} chars)', ['number' => $length]);

            case 'url':
                return __('URL ({number} chars)', ['number' => $length]);

            case 'numeric':
                return __('Number');
        }

        switch ($kind) {
            case 'char':
                return __('Text ({number} chars)', ['number' => $length]);

            case 'text':
                return $filter != 'string' ? __('Text') . ' (' . $filter . ')' : __('Text');

            case 'integer':
                return __('Number ({number} digits)', ['number' => $length]);

            case 'decimal':
                $scale = $this->getField($fieldName, 'scale');
                $format = str_repeat('0', $length) . "." . str_repeat('0', $scale);
                return __('Decimal ({number} format)', ['number' => $format]);

            case 'date':
                return __('Date');

            case 'yesno':
                return __('Y or N');

            case 'boolean':
                return __('True or False');

            case 'enum':
                $options = implode('<br/>', $this->getField($fieldName, 'elements'));
                return '<abbr title="' . $options . '">' . __('Options') . '</abbr>';

            default:
                return __(ucfirst($kind));
        }

        return '';
    }

    /**
     * Get the tables used in this import. All tables used must be locked.
     *
     * @return  array   2D array of table names used in this import
     */
    public function getTables()
    {
        return array_keys($this->tables);
    }

    /**
     * Get Primary Key
     *
     * @return  array   2D array of available keys to sync with
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Get Table Fields
     *
     * @return  array   2D array of table field names used in this import
     */
    public function getTableFields()
    {
        return (isset($this->table)) ? array_keys($this->table) : [];
    }

    /**
     * @return array
     */
    public function getJoin(): array
    {
        return $this->join;
    }

    /**
     * setJoin
     * @param array $join
     * @return ImportReport
     * @throws MissingClassException
     */
    public function setJoin(array $join): ImportReport
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'type' => 'join',
        ]);
        $resolver->setRequired([
            'table',
            'alias',
            'reference',
            'targetTable'
        ]);
        $resolver->setAllowedTypes('table', 'string');
        $resolver->setAllowedTypes('reference', 'string');
        $resolver->setAllowedTypes('targetTable', 'string');
        $resolver->setAllowedValues('type', ['join', 'leftJoin']);

        foreach ($join as $field => $item) {
            if (!isset($item['reference']))
                $item['reference'] = lcfirst($field);
            if (!isset($item['targetTable']))
                $item['targetTable'] = $field;

            $join[$field] = $resolver->resolve($item);
            if (!class_exists('\App\Entity\\' . $item['targetTable']))
                throw new MissingClassException(sprintf('The class %s does not exists.', '\App\Entity\\' . $item['targetTable']));
            if (!class_exists('\App\Entity\\' . $item['table']))
                throw new MissingClassException(sprintf('The class %s does not exists.', '\App\Entity\\' . $item['table']));
        }

        $this->join = $join;

        return $this;
    }

    /**
     * getJoinAlias
     * @param string $name
     * @return string
     */
    public function getJoinAlias(string $name): string
    {
        if (!isset($this->getJoin()[$name]))
            return $this->getDetail('alias');
        return $this->getJoin()[$name]['alias'];
    }

    /**
     * @return array|mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getTablesUsed(): array
    {
        return $this->tablesUsed;
    }
}