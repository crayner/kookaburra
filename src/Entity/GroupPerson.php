<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Kookaburra\UserAdmin\Entity\Person;

/**
 * Class GroupPerson
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\GroupPersonRepository")
 * @ORM\Table(options={"auto_increment": 1}, name="GroupPerson", uniqueConstraints={@ORM\UniqueConstraint(name="gibbonGroupID", columns={"gibbonGroupID", "gibbonPersonID"})})
 */
class GroupPerson
{
    /**
     * @var integer|null
     * @ORM\Id
     * @ORM\Column(type="integer", name="gibbonGroupPersonID", columnDefinition="INT(10) UNSIGNED AUTO_INCREMENT")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @var Group|null
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="people")
     * @ORM\JoinColumn(name="gibbonGroupID", referencedColumnName="gibbonGroupID", nullable=false)
     */
    private $group;

    /**
     * @var Person|null
     * @ORM\ManyToOne(targetEntity="Kookaburra\UserAdmin\Entity\Person")
     * @ORM\JoinColumn(name="gibbonPersonID", referencedColumnName="id", nullable=false)
     */
    private $person;
}