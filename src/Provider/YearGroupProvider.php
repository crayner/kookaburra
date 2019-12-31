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
 * Date: 10/09/2019
 * Time: 18:01
 */

namespace App\Provider;

use App\Entity\YearGroup;
use App\Manager\Traits\EntityTrait;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;

/**
 * Class YearGroupProvider
 * @package App\Provider
 */
class YearGroupProvider implements EntityProviderInterface
{
    use EntityTrait;

    private $entityName = YearGroup::class;

    /**
     * getCurrentStaffChoiceList
     * @return array
     * @throws \Exception
     */
    public function getCurrentYearGroupChoiceList(): array {
        $result = [];
        foreach($this->getRepository()->findCurrentYearGroups() as $q=>$w){
            $result[]= new ChoiceView([], $w->getId(), $w->getName(), []);
        }
        return $result;
    }

}