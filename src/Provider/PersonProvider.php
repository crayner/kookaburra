<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 09:47
 */

namespace App\Provider;

use App\Entity\Person;
use App\Manager\Traits\EntityTrait;
use App\Security\SecurityUser;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PersonProvider implements EntityProviderInterface, UserLoaderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = Person::class;

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        $person = $this->getRepository()->loadUserByUsernameOrEmail($username);

        return $person ? new SecurityUser($person) : null;
    }
}