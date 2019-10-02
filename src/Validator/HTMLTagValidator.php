<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 2/10/2019
 * Time: 10:32
 */

namespace App\Validator;

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class HTMLTagValidator
 * @package App\Validator
 */
class HTMLTagValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $comment = html_entity_decode($value);
        $allowableTags = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'allowableHTML');
        $allowableTags = preg_replace("/\[([^\[\]]|(?0))*]/", '', $allowableTags);
        $allowableTagTokens = explode(',', $allowableTags);
        $allowableTags = '';
        foreach ($allowableTagTokens as $allowableTagToken) {
            $allowableTags .= '&lt;'.$allowableTagToken.'&gt;';
        }
        $allowableTags = html_entity_decode($allowableTags);

        if ($comment !== strip_tags($comment, $allowableTags))
            $this->context->buildViolation('The HTML contains tags that are not allowed. Valid tags are "{tags}"')
                ->setTranslationDomain('messages')
                ->setParameter('{tags}', str_replace('><', '>,<', $allowableTags))
                ->addViolation();
    }
}