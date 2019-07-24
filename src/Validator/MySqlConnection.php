<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/07/2019
 * Time: 14:25
 */

namespace App\Validator;


use Doctrine\DBAL\Connection;
use Symfony\Component\Validator\Constraint;

class MySqlConnection extends Constraint
{
    /**
     * getTargets
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * validatedBy
     * @return string
     */
    public function validatedBy()
    {
        return MySQLConnectionValidator::class;
    }
}