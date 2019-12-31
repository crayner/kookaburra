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
 * Date: 13/11/2019
 * Time: 11:21
 */

namespace App\Twig\Extension;

use App\Manager\ScriptManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ScriptExtension
 * @package App\Twig\Extension
 */
class ScriptExtension extends AbstractExtension
{
    /**
     * @var ScriptManager
     */
    private $manager;

    /**
     * ScriptExtension constructor.
     * @param ScriptManager $manager
     */
    public function __construct(ScriptManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * getFunctions
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('addPageScript', [$this, 'addPageScript']),
        ];
    }

    /**
     * addPageScript
     * @param string $script
     * @param array $params
     * @return string
     */
    public function addPageScript(string $script, array $params = []): string
    {
        $this->manager->addPageScript($script,$params);
        return '';
    }
}