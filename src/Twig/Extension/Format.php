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
 * Date: 6/08/2019
 * Time: 15:38
 */

namespace App\Twig\Extension;


use Kookaburra\SystemAdmin\Entity\I18n;
use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Symfony\Component\Intl\Currencies;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Format extends AbstractExtension
{

    /**
     * getFunctions
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('formatSetting', [$this, 'formatSetting']),
            new TwigFunction('dateFormat', [$this, 'dateFormat']),
            new TwigFunction('formatCurrency', [$this, 'formatCurrency']),
        ];
    }

    /**
     * formatSetting
     * @param string $name
     * @return string
     */
    public function formatSetting(string $name): string
    {
        return \App\Util\Format::getSetting($name);
    }

    /**
     * dateFormat
     * Formats a YYYY-MM-DD date with the language-specific format. Optionally provide a format string to use instead.
     *
     * @return mixed
     */
    public static function dateFormat()
    {
        return ProviderFactory::create(I18n::class)->getDatePHPFormat();
    }

    /**
     * formatCurrency
     * @param float $value
     */
    public function formatCurrency(float $value)
    {
        $code = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'currency', 'USD');
        $result = '';
        if (Currencies::exists($code)) {
            $result .= Currencies::getSymbol($code);
            $result .= number_format($value, Currencies::getFractionDigits($code));
        }
        return $result === '' ? $value : $result;
    }
}
