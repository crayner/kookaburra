<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 5/09/2019
 * Time: 09:37
 */

namespace App\Form\EventSubscriber;

use App\Util\JsonFileUploadHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Validation;

/**
 * Class ReactFileListener
 * @package App\Form\EventSubscriber
 */
class ReactFileListener implements EventSubscriberInterface
{
    /**
     * @var RequestStack
     */
    private $stack;

    /**
     * ReactFileListener constructor.
     * @param RequestStack $stack
     */
    public function __construct(RequestStack $stack)
    {
        $this->stack = $stack;
    }

    /**
     * getSubscribedEvents
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::SUBMIT => 'saveFile',
        ];
    }

    /**
     * saveFile
     * @param SubmitEvent $event
     */
    public function saveFile(SubmitEvent $event)
    {
        $request = $this->stack->getCurrentRequest();
        if ($request->getContentType() === 'json') {
            $targetPath = realpath(__DIR__ . '/../../../public/uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');
            $form = $event->getForm();
            $data = $form->getData();
            $value = $this->getContentValue($form->getName(), json_decode($request->getContent(), true));
            if ('' === $value) {
                $event->setData($data);
                return;
            }
            $file = JsonFileUploadHelper::saveFile($value, $form->getConfig()->getOption('file_prefix'));
            $validator = Validation::createValidator();
            $x = $validator->validate($file, $form->getConfig()->getOption('constraints'));
            if ($x->count() > 0) {
                unlink($file->getRealPath());
                foreach ($x as $constraint)
                    $form->addError(new FormError($constraint->getMessage()));
                $data = null;
            } else {
                $public = realpath(__DIR__ . '/../../public');
                $data = $file->getRealPath();

                // Remove existing file..
                $file = $form->getData();
                if (!in_array($file, [null,'']) && $file !== $data) {
                    $file = realpath($file) ?: ($public . DIRECTORY_SEPARATOR . $file ?: false);
                    if (false !== $file && is_file($file))
                        unlink($file);
                }
            }
            $event->setData($data);
        }
    }

    /**
     * getContentValue
     * @param string $name
     * @param array $content
     * @param null $value
     * @return string|null
     */
    public function getContentValue(string $name, array $content, $value = null): ?string
    {
        foreach($content as $q=>$w)
        {
            if ($q === $name)
                $value = $w;

            if (is_array($w) && $value === null)
                $value = $this->getContentValue($name, $w, $value);

            if (null !== $value)
                break;
        }
        return $value;
    }

    /**
     * @return RequestStack
     */
    public function getStack(): RequestStack
    {
        return $this->stack;
    }
}