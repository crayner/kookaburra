<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 10:04
 */

namespace App\Provider;

use App\Entity\I18n;
use App\Manager\Traits\EntityTrait;

class I18nProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = I18n::class;
}