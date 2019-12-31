<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 12:30
 */

namespace App\Util;

use App\Provider\ProviderFactory;
use Doctrine\Common\Persistence\ObjectRepository;

class EntityHelper
{
    /**
     * @var ProviderFactory
     */
    private static $providerFactory;

    /**
     * EntityHelper constructor.
     * @param ProviderFactory $providerFactory
     */
    public function __construct(ProviderFactory $providerFactory)
    {
        self::$providerFactory = $providerFactory;
    }

    /**
     * @return ProviderFactory
     */
    public static function getProviderFactory(): ProviderFactory
    {
        return self::$providerFactory;
    }

    /**
     * getRepository
     * @param string $entityName
     * @return ObjectRepository
     */
    public static function getRepository(string $entityName): ObjectRepository
    {
        $provider = self::getProviderFactory();
        return $provider::getRepository($entityName);
    }
}