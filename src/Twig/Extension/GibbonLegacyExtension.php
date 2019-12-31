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
 * Date: 28/06/2019
 * Time: 13:01
 */

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GibbonLegacyExtension extends AbstractExtension
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'gibbon_lagacy_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('__', [$this, '__']),
            new TwigFunction('getMessageManager', [$this, 'getMessageManager']),
        ];
    }

    /**
     * Custom translations function to allow custom string replacement
     *
     * @param string        $text    Text to Translate.
     * @param array         $params  Assoc array of key value pairs for named
     *                               string replacement.
     * @param array|string  $options Options for translations (e.g. domain).
     *                               Or string of domain (for backward
     *                               compatibility, deprecated).
     *
     * @return string The resulted translations string.
     */
    public function __($text, $params=[], $options=[]): string
    {
        return __($text, $params, $options);
    }
}