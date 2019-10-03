<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 3/10/2019
 * Time: 12:31
 */

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class ScaleGrade
 * @package App\Validator
 * @Annotation
 */
class ScaleGrade extends Constraint
{
    /**
     * getTargets
     * @return array|string
     */
    public function getTargets()
    {
        return Constraint::CLASS_CONSTRAINT;
    }
}