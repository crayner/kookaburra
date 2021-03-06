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
 * Date: 19/07/2019
 * Time: 11:37
 */

namespace App\Provider;

use App\Entity\Log;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\UserAdmin\Entity\Person;
use Kookaburra\SchoolAdmin\Entity\AcademicYear;
use App\Manager\Traits\EntityTrait;
use App\Util\GlobalHelper;

/**
 * Class LogProvider
 * @package App\Provider
 */
class LogProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string 
     */
    private $entityName = Log::class;


    /**
     * setLog
     * @param int|AcademicYear|null $schoolYearID
     * @param int|Module|null $moduleID
     * @param int|Person|null $personID
     * @param $title
     * @param null $array
     * @param null $ip
     * @return int|null
     */
    public static function setLog($schoolYearID = null, $moduleID = null, $personID = null, string $title, array $array = null, string $ip = null): ?int
    {
        if ((!is_array($array) && $array != null) || $title == null || $schoolYearID == null) {
            return null;
        }

        $ip = GlobalHelper::getIPAddress($ip);

        $log = new Log();
        $provider = ProviderFactory::create(Log::class);
        $provider->setEntity($log);
        $person = $personID instanceof Person ? $personID : $provider->getRepository(Person::class)->find(intval($personID));
        $module = $moduleID instanceof Module ? $moduleID : $provider->getRepository(Module::class)->find(intval($moduleID));
        $schoolYear = $schoolYearID instanceof AcademicYear ? $schoolYearID : $provider->getRepository(AcademicYear::class)->find(intval($schoolYearID));
        $log->setPerson($person)
            ->setModule($module)
            ->setSchoolYear($schoolYear)
            ->setIp($ip)
            ->setTitle($title)
            ->setSerialisedArray($array);
        $provider->saveEntity();

        return $log->getId();
    }
}