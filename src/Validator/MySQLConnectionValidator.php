<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 24/07/2019
 * Time: 14:26
 */

namespace App\Validator;

use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Doctrine\DBAL\Exception\DriverException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class MySQLConnectionValidator
 * @package App\Validator
 */
class MySQLConnectionValidator extends ConstraintValidator
{
    /**
     * validate
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        try {
            $driver = new Driver();
            $conn = $driver->connect($value->getParams(false), $value->getUser(), $value->getPassword(), []);
        } catch(DriverException $e) {
            $this->context->buildViolation('The MySQL Connection Settings did not connect. [%{message}]')
                ->setParameter('%{message}',$e->getMessage())
                ->setTranslationDomain('kookaburra')
                ->addViolation();
            return ;
        }


        $sql = "CREATE DATABASE IF NOT EXISTS " . $value->getDbname() . " DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci";
        try {
            $conn->exec($sql);
        } catch (PDOException $e) {
            $this->context->buildViolation('The database does not exist and cannot be created. [%{message}]')
                ->setParameter('%{message}', $e->getMessage())
                ->setTranslationDomain('kookaburra')
                ->addViolation();
            return ;
        }

        try {
            $driver = new Driver();
            $conn = $driver->connect($value->getParams(), $value->getUser(), $value->getPassword(), []);
        } catch(DriverException $e) {
            $this->context->buildViolation('The MySQL Connection Settings did not connect. [%{message}]')
                ->setParameter('%{message}',$e->getMessage())
                ->setTranslationDomain('kookaburra')
                ->addViolation();
            return ;
        }
    }
}