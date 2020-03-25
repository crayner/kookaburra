<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 6/01/2020
 * Time: 17:56
 */

namespace App\Form\EventSubscriber;

use App\Provider\ProviderFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class SimpleArrayToEntitySubcriber
 * @package App\Form\EventSubscriber
 */
class SimpleArrayToEntitySubcriber implements EventSubscriberInterface
{
    /**
     * @var array
     */
    private $options;

    /**
     * SimpleArrayToEntitySubcriber constructor.
     * @param array|null $options
     */
    public function __construct(?array $options = null)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
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
        $data = $event->getData();
        $transform = true;
        foreach($data As $w)
            if ($w instanceof $this->options['class'])
            {
                $transform = false;
                break;
            }
        if ($transform)
            $data = ProviderFactory::getRepository($this->options['class'])->findAllInArray($data);
        $event->setData($data);
    }
}