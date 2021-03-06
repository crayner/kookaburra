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
 * Date: 11/08/2019
 * Time: 08:22
 */

namespace App\Provider;

use App\Entity\Course;
use Kookaburra\Departments\Entity\Department;
use App\Manager\Traits\EntityTrait;

/**
 * Class CourseProvider
 * @package App\Provider
 */
class CourseProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Course::class;

    /**
     * getByDepartment
     * @param Department $department
     * @return array
     */
    public function getByDepartment(Department $department): array
    {
        return $this->getRepository()->findByDepartment($department, $this->getSession()->get('academicYear'));
    }
}