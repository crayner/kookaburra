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
 * Date: 9/08/2019
 * Time: 10:41
 */

namespace App\Util;

use App\Entity\Setting;
use App\Provider\ProviderFactory;

/**
 * Class MailerHelper
 * @package App\Util
 */
class MailerHelper
{
    /**
     * defaultOptions
     * @return array
     */
    public static function defaultOptions(): array
    {
        $provider = ProviderFactory::create(Setting::class);
        return [
            'organisationLogo'  => $provider->getSettingByScopeAsString('System', 'organisationLogo'),
            'organisationName'  => $provider->getSettingByScopeAsString('System', 'organisationName'),
            'systemName'        => $provider->getSettingByScopeAsString('System', 'systemName'),
        ];
    }
}