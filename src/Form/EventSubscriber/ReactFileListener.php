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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
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
            FormEvents::PRE_SUBMIT => 'saveFile',
        ];
    }

    /**
     * saveFile
     * @param PreSubmitEvent $event
     */
    public function saveFile(PreSubmitEvent $event)
    {
        $request = $this->stack->getCurrentRequest();
        if ($request->getContentType() === 'json') {
            $form = $event->getForm();
            $value = $this->getContentValue($form->getName(), json_decode($request->getContent(), true));

            if (preg_match('#^data:[^;]*;base64,#', $value) === 1) {
                $targetPath = realpath(__DIR__ . '/../../../public/uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR . date('m');
                $target = DIRECTORY_SEPARATOR . 'temp' . uniqid() . '.txt';
                $value = explode(',', $value);
                if (!is_dir($targetPath))
                    mkdir($targetPath, '0755', true);
                file_put_contents($targetPath . $target, base64_decode($value[1]));

                $file = new File($targetPath . $target, true);

                $fileName = substr(trim($form->getConfig()->getOption('fileName'), '_') . '_' . uniqid(), 0, 32) . '.' . $file->guessExtension();
                $validator = Validation::createValidator();
                $file->move($targetPath, $fileName);
                $file = new File($targetPath . DIRECTORY_SEPARATOR . $fileName, true);
                $x = $validator->validate($file, $form->getConfig()->getOption('constraints'));
                if ($x->count() > 0) {
                    unlink($targetPath . DIRECTORY_SEPARATOR . $fileName);
                    foreach ($x as $constraint)
                        $form->addError(new FormError($constraint->getMessage()));

                    $data = null;
                } else {
                    $public = realpath(__DIR__ . '/../../../public');
                    $data = str_replace($public, '', $file->getRealPath());

                    // Remove existing file..
                    $file = $form->getData();
                    if (!in_array($file, [null,''])) {
                        $file = realpath($file) ?: ($public . DIRECTORY_SEPARATOR . $file ?: false);
                        if (false !== $file && is_file($file))
                            unlink($file);
                    }
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
    private function getContentValue(string $name, array $content, $value = null): ?string
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
    
    /*                    if ($form->getConfig()->getOption('type') === 'file' && !$data instanceof File)
                    {
                        $value = $this->getContentValue($name, $content);
                        if ($data !== $value && preg_match('#^data:[^;]*;base64,#', $value) === 1) {
                            $target = realpath(__DIR__ . '/../../public/uploads') . DIRECTORY_SEPARATOR .date('Y') . DIRECTORY_SEPARATOR .date('m') . DIRECTORY_SEPARATOR . 'temp'.uniqid().'.txt';
                            $value = explode(',', $value);
                            if (!is_dir(realpath(__DIR__ . '/../../public/uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR .date('m')))
                                mkdir(realpath(__DIR__ . '/../../public/uploads') . DIRECTORY_SEPARATOR . date('Y') . DIRECTORY_SEPARATOR .date('m'), '0755', true);
                            file_put_contents($target,base64_decode($value[1]));

                            $file = new File($target, true);

                            $fileName = substr(trim( $form->getConfig()->getOption('fileName'), '_'). '_' . uniqid(), 0, 32) . '.' . $file->guessExtension();
                            $target = realpath(__DIR__ . '/../../public/uploads') . DIRECTORY_SEPARATOR .date('Y') . DIRECTORY_SEPARATOR .date('m') . DIRECTORY_SEPARATOR . $fileName;
                            $validator = Validation::createValidator();
                            $file->move(dirname($target), $fileName);
                            $file = new File(dirname($target) . '/' . $fileName, true);
                            $x = $validator->validate($file, $form->getConfig()->getOption('constraints'));
                            if ($x->count() > 0) {
                                unlink(dirname($target) . '/' . $fileName);
                                foreach($x as $constraint) {
                                    $form->addError(new FormError($constraint->getMessage()));
                                    $this->addError(['class' => 'error', 'message' => $translator->trans('Your request failed because your inputs were invalid.')]);
                                }
                                $data = null;
                            } else {
                                $data = str_replace($target = realpath(__DIR__ . '/../../public'),'', $file->getRealPath());
                                dump($data);
                                // Remove existing File...
                                $file = $this->getSettingByScopeAsString($setting['scope'], $setting['name']);
                                if (!in_array($file, [null, ''])) {
                                    $file = realpath($file) ?: (realpath(__DIR__ . '/../../public' . $file) ?: false);
                                    dump($file);
                                    if (false !== $file)
                                        unlink($file);
                                }
                            }
                        }
                    }
*/
}