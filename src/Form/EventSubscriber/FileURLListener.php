<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 6/09/2019
 * Time: 09:05
 */

namespace App\Form\EventSubscriber;

use Symfony\Component\Form\Event\SubmitEvent;

/**
 * Class FileURLListener
 * @package App\Form\EventSubscriber
 */
class FileURLListener extends ReactFileListener
{
    /**
     * saveFile
     * @param SubmitEvent $event
     */
    public function saveFile(SubmitEvent $event)
    {
        $request = $this->getStack()->getCurrentRequest();
        $form = $event->getForm();
        $value = $this->getContentValue($form->getName(), json_decode($request->getContent(), true));
        if (strpos($value, 'http') === 0)
        {
            $event->setData($value);
            return ;
        }
        parent::saveFile($event);
    }
}