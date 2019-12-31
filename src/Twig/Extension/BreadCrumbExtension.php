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
 * Time: 08:35
 */

namespace App\Twig\Extension;

use App\Manager\Entity\BreadCrumbs;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class BreadCrumbExtension
 * @package App\Twig\Extension
 */
class BreadCrumbExtension extends AbstractExtension
{

    /**
     * @var BreadCrumbs
     */
    private $breadCrumbs;

    /**
     * BreadCrumbExtension constructor.
     * @param Request $request
     */
    public function __construct(BreadCrumbs $breadCrumbs) {
        $this->breadCrumbs = $breadCrumbs;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'breadcrumb_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('createBreadCrumbs', [$this->breadCrumbs, 'create']),
        ];
    }
}