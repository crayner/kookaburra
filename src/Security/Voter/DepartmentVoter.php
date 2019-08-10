<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 10/08/2019
 * Time: 14:14
 */

namespace App\Security\Voter;

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

/**
 * Class DepartmentVoter
 * @package App\Security\Voter
 */
class DepartmentVoter implements VoterInterface
{
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        if (in_array('ROLE_DEPARTMENT', $attributes) && ProviderFactory::create(Setting::class)->getSettingByScopeAsBoolean('Departments', 'makeDepartmentsPublic'))
        {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

}