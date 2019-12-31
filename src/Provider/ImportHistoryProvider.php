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
 * Date: 20/09/2019
 * Time: 14:46
 */

namespace App\Provider;

use App\Entity\ImportHistory;
use App\Manager\Traits\EntityTrait;

/**
 * Class ImportHistoryProvider
 * @package App\Provider
 */
class ImportHistoryProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = ImportHistory::class;

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