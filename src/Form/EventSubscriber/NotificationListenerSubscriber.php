<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 13/09/2019
 * Time: 08:52
 */

namespace App\Form\EventSubscriber;

use Kookaburra\UserAdmin\Entity\Person;
use App\Entity\YearGroup;
use Kookaburra\SchoolAdmin\Util\AcademicYearHelper;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvents;

/**
 * Class NotificationListenerSubscriber
 * @package App\Form\EventSubscriber
 */
class NotificationListenerSubscriber implements EventSubscriberInterface
{
    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'addScopeID',
        ];
    }

    /**
     * addScopeID
     * @param PreSubmitEvent $event
     */
    public function addScopeID(PreSubmitEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        if ($data['scopeType'] === 'All') {
            $options = $form->get('scopeID')->getConfig()->getOptions();
            $form->remove('scopeID');
            $form->add('scopeID', HiddenType::class, array_merge($options, [
                'data' => null,
            ]));
        }

        if ($data['scopeType'] === 'gibbonPersonIDStudent') {
            $options = $form->get('scopeID')->getConfig()->getOptions();
            $form->remove('scopeID');
            $schoolYear = AcademicYearHelper::getCurrentAcademicYear();
            $today = new \DateTime(date('Y-m-d'));
            $form->add('scopeID', EntityType::class, array_merge($options, [
                'label' => 'Scope Type Choice',
                'class' => Person::class,
                'query_builder' => function(EntityRepository $er) use ($schoolYear,$today) {
                    return $er->createQueryBuilder('p')
                        ->join('p.studentEnrolments','se')
                        ->where('se.academicYear = :academicYear')
                        ->setParameter('academicYear', $schoolYear)
                        ->andWhere('p.status = :full')
                        ->setParameter('full', 'Full')
                        ->andWhere('(p.dateStart IS NULL OR p.dateStart <= :today)')
                        ->andWhere('(p.dateEnd IS NULL OR p.dateEnd >= :today)')
                        ->setParameter('today', $today)
                        ->orderBy('p.surname', 'ASC')
                        ->addOrderBy('p.preferredName', 'ASC')
                    ;
                },
                'choice_label' => 'formatName',
                'choice_translation_domain' => false,
                'data_class' => null,
            ]));
        }

        if ($data['scopeType'] === 'gibbonPersonIDStaff') {
            $options = $form->get('scopeID')->getConfig()->getOptions();
            $form->remove('scopeID');
            $today = new \DateTime(date('Y-m-d'));
            $form->add('scopeID', EntityType::class, array_merge($options, [
                'label' => 'Scope Type Choice',
                'class' => Person::class,
                'query_builder' => function(EntityRepository $er) use ($today) {
                    return $er->createQueryBuilder('p')
                        ->join('p.staff','s')
                        ->where('s.id IS NOT NULL')
                        ->andWhere('p.status = :full')
                        ->setParameter('full', 'Full')
                        ->andWhere('(p.dateStart IS NULL OR p.dateStart <= :today)')
                        ->andWhere('(p.dateEnd IS NULL OR p.dateEnd >= :today)')
                        ->setParameter('today', $today)
                        ->orderBy('p.surname')
                        ->addOrderBy('p.preferredName')
                        ;
                },
                'choice_label' => 'formatName',
                'choice_translation_domain' => false,
                'data_class' => null,
            ]));
        }

        if ($data['scopeType'] === 'gibbonYearGroupID') {
            $options = $form->get('scopeID')->getConfig()->getOptions();
            $form->remove('scopeID');
            $form->add('scopeID', EntityType::class, array_merge($options, [
                'class' => YearGroup::class,
                'label' => 'Scope Type Choice',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('yg')
                        ->orderBy('yg.sequenceNumber')
                    ;
                },
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'data_class' => null,
            ]));
        }
    }
}