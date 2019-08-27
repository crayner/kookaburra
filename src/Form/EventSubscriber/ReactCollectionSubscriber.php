<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/08/2019
 * Time: 08:49
 */

namespace App\Form\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvents;

/**
 * Class ReactCollectionSubscriber
 * @package App\Form\EventSubscriber
 */
class ReactCollectionSubscriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * ReactCollectionSubscriber constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * preSetData
     * @param PreSetDataEvent $event
     */
    public function preSetData(PreSetDataEvent $event)
    {
        foreach($event->getForm()->all() as $child)
        {
            $child->add($this->options['element_id_name'], HiddenType::class);
        }
    }
}