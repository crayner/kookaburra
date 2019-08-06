<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 23/11/2018
 * Time: 15:27
 */
namespace App\Repository;

use App\Entity\Notification;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class NotificationRepository
 * @package App\Repository
 */
class NotificationRepository extends ServiceEntityRepository
{
    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findByPerson(Person $person)
    {
        $results = $this->createQueryBuilder('n')
            ->select(['n', 'm.name AS source'])
            ->join('n.module', 'm')
            ->where('n.status = :new')
            ->andWhere('n.person = :person')
            ->setParameters(['new' => 'New', 'person' => $person])
            ->getQuery()
            ->getResult();
       $results = array_merge($results, $this->createQueryBuilder('n')
            ->select(['n', "'System' AS source"])
            ->where('n.status = :new')
            ->andWhere('n.person = :person')
            ->andWhere('n.module IS NULL')
            ->setParameters(['new' => 'New', 'person' => $person])
            ->getQuery()
            ->getResult());
        $results = new ArrayCollection($results);

        $iterator = $results->getIterator();
        $iterator->uasort(
            function ($a, $b) {
                return $a['timeStamp']->getTimeStamp() . $a['source'] . $a['text'] < $b['timestamp']->getTimeStamp() . $b['source'] . $b['text'] ? -1 : 1 ;
            }
        );
        $results  = new ArrayCollection(iterator_to_array($iterator, false));

        return $results->toArray();
    }
}
