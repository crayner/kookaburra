<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/07/2019
 * Time: 14:57
 */

namespace App\Provider;

use App\Entity\Role;
use App\Manager\Traits\EntityTrait;
use PDOException;

/**
 * Class RoleProvider
 * @package App\Provider
 */
class RoleProvider implements EntityProviderInterface
{
    use EntityTrait;

    private $entityName = Role::class;

    /**
     * getRoleList
     * @param $roleList
     * @return mixed
     * @throws \Exception
     */
    public function getRoleList($roleList)
    {
        return $this->getRepository()->getRoleList($roleList);
    }
}