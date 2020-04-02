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
 * Date: 8/08/2019
 * Time: 12:30
 */

namespace App\Validator;

use Kookaburra\SystemAdmin\Entity\Setting;
use App\Provider\ProviderFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class RegistrationMinimumAgeValidator
 * @package App\Validator
 */
class RegistrationMinimumAgeValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime)
            return ;

        $minimumAge = ProviderFactory::create(Setting::class)->getSettingByScopeAsInteger('User Admin', 'publicRegistrationMinimumAge', 0);
        $interval = $value->diff(new \DateTime());

        if ($interval->y < $minimumAge)
            $this->context->buildViolation($constraint->message)
                ->setParameter('{oneString}', $minimumAge)
                ->setTranslationDomain('messages')
                ->addViolation();
    }

}