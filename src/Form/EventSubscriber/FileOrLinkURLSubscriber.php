<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/08/2019
 * Time: 15:00
 */

namespace App\Form\EventSubscriber;


use App\Util\FileHelper;
use App\Validator\URLOrFile;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class FileOrLinkURLSubscriber
 * @package App\Form\EventSubscriber
 */
class FileOrLinkURLSubscriber implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * @var string
     */
    private $targetDir;

    /**
     * FileOrLinkURLSubscriber constructor.
     * @param RequestStack $stack
     * @param string $targetDir
     */
    public function __construct(RequestStack $stack, string $targetDir)
    {
        $this->stack = $stack;
        $this->targetDir = $targetDir;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
            FormEvents::PRE_SET_DATA => 'preSetData',
        ];
    }

    /**
     * preSubmit
     * @param PreSubmitEvent $event
     */
    public function preSubmit(PreSubmitEvent $event)
    {
        $data = $event->getData();
        if (null === $data) return;
        $department = $event->getForm()->getParent()->getData();
        foreach($data as $q=>$w) {
            if ($w['url'] instanceof UploadedFile)
                $w['url'] = $w['url']->move($this->targetDir, $w['url']->getClientOriginalName());

            if ($w['type'] === '')
                $w['type'] = 'Link';

            if ($w['type'] === 'File' && ('' === $w['url'] || null === $w['url']))
                $w['url'] = $event->getForm()->get($q)->get('url')->getData();

            if ($w['department'] === '')
                $w['department'] = strval($department->getId());

            if (isset($w['id']))
                unset($w['id']);

            $data[$q] = $w;
        }
        $event->setData($data);
    }

    /**
     * preSetData
     * @param PreSetDataEvent $event
     */
    public function preSetData(PreSetDataEvent $event)
    {
 /*       $data = $event->getForm()->getParent()->getData();
        foreach($event->getForm()->all() as $q=>$child)
        {
            $dr = $data->getResources()->get($q);
            if ($dr->getType() === 'File') {
                $child->remove('url');
                $child->add('url', FileType::class,
                    [
                        'label' => 'Resource Location',
                        'constraints' => [
                            new URLOrFile(),
                        ],
                    ]
                );
            }
        }
*/
    }
}