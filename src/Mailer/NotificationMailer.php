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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class NotificationMailer
 * @package App\Mailer
 */
class NotificationMailer
{
    /**
     * @var TranslatorInterface
     */
    private $trans;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * NotificationMailer constructor.
     * @param MailerInterface $mailer
     * @param TranslatorInterface $trans
     */
    public function __construct(MailerInterface $mailer, TranslatorInterface $trans)
    {
        $this->trans = $trans;
        $this->mailer = $mailer;
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
            ->to(new Address($person->getEmail(), $person->formatName()))
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

        $this->getMailer()->send($email);
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

    /**
     * @return MailerInterface
     */
    public function getMailer(): MailerInterface
    {
        return $this->mailer;
    }
}