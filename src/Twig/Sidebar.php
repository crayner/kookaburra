<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 16:43
 */
namespace App\Twig;

use App\Security\SecurityUser;
use App\Util\UserHelper;

class Sidebar implements ContentInterface
{
    use ContentTrait;

    /**
     * execute
     */
    public function execute(): void
    {
        $user = UserHelper::getSecurityUser();
        $this->content = false;

        if ($user instanceof SecurityUser) {
            // Show role switcher if user has more than one role
            if (count($user->getAllRolesAsArray()) > 1 && false === $this->getRequest()->get('address')) {

                $this->addContent('role_switcher', true);
            }
        }
    }
}