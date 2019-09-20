<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/09/2019
 * Time: 14:46
 */

namespace App\Provider;


use App\Entity\ImportRecord;
use App\Manager\Traits\EntityTrait;

class ImportRecordProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ImportRecord::class;

    /**
     * findLastModifiedByName
     * @param string $name
     * @return string|null
     */
    public function findLastColumnOrderByName(string $name): ?string
    {
        $w = $this->getRepository()->findLastModifiedByName($name);
        return $w ? $w->getColumnOrder() : null;
    }
}