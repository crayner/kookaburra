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
 * Date: 27/07/2019
 * Time: 11:04
 */

namespace App\Provider;

use App\Entity\RollGroup;
use App\Entity\StudentEnrolment;
use App\Manager\Traits\EntityTrait;

class RollGroupProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = RollGroup::class;
}