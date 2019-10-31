<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/08/2019
 * Time: 15:04
 */

namespace App\Mailer;

use Kookaburra\SystemAdmin\Entity\Notification;
use App\Entity\Person;
use App\Util\MailerHelper;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\NamedAddress;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class NotificationMailer
 * @package App\Mailer
 */
class NotificationMailer extends Mailer
{
    /**
     * @var TranslatorInterface
     */
    private $trans;

    /**
     * NotificationMailer constructor.
     * @param TranslatorInterface $trans
     * @param TransportInterface $transport
     * @param MessageBusInterface|null $bus
     */
    public function __construct(TranslatorInterface $trans, TransportInterface $transport, MessageBusInterface $bus = null)
    {
        $this->trans = $trans;
        parent::__construct($transport, $bus);
    }

    /**
     * newRegistration
     * @param Person $person
     * @param string $message
     * @param array $messageOptions
     * @param string $moduleName
     * @param string $actionLink
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function newRegistration(Person $person, string $message, array $messageOptions, string $moduleName, string $actionLink, Notification $notification)
    {
        $email = (new TemplatedEmail())
            ->to(new NamedAddress($person->getEmail(), $person->formatName()))
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($this->translate('Notification').' - '.$moduleName)
            ->htmlTemplate('email/notification.html.twig')
            ->context(array_merge(MailerHelper::defaultOptions(), [
                'title' => 'Notification - {name}',
                'titleOptions' => ['{name}' => $moduleName],
                'body' => $message,
                'bodyOptions' => $messageOptions,
                'button' => [
                    'route'  => 'notification_action',
                    'routeOptions' => ['notification' => $notification->getId()],
                    'text' => 'View Details',
                ],
                'notification' => $notification,
            ]));

        $this->send($email);
    }

    /**
     * translate
     * @param string $message
     * @param array $options
     * @param string $domain
     * @return string
     */
    public function translate(string $message, array $options = [], string $domain = 'messages'): string
    {
        return $this->trans->trans($message, $options, $domain);
    }
}