<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 28/06/2019
 * Time: 15:00
 */

namespace App\Provider;

use App\Entity\Theme;
use App\Manager\Traits\EntityTrait;

class ThemeProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Theme::class;
}