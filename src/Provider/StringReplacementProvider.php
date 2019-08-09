<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/08/2019
 * Time: 13:21
 */

namespace App\Provider;

use App\Entity\StringReplacement;
use App\Manager\Traits\EntityTrait;

/**
 * Class StringReplacementProvider
 * @package App\Provider
 */
class StringReplacementProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = StringReplacement::class;
}