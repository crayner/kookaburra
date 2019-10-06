<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 2/10/2019
 * Time: 09:50
 */

namespace App\Logger;

use App\Entity\Person;
use Kookaburra\UserAdmin\Util\UserHelper;

/**
 * Class UErDetailProcessor
 * @package App\Logger
 */
class UserDetailProcessor
{
    /**
     * @var null|Person
     */
    private $user;

    /**
     * __invoke
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        $record['extra']['user']['id'] = $this->getUserId();
        $record['extra']['user']['name'] = $this->getUserName();

        return $record;
    }

    /**
     * @return Person|null
     */
    public function getUser(): ?Person
    {
        return $this->user = $this->user ?: UserHelper::getCurrentUser();
    }

    /**
     * getUserId
     * @return string
     */
    public function getUserId(): string
    {
        return $this->getUser() ? strval(intval($this->getUser()->getId())) : '';
    }

    /**
     * getUserId
     * @return string
     */
    public function getUsername(): string
    {
        return $this->getUser() ? $this->getUser()->formatName() : '';
    }
}
