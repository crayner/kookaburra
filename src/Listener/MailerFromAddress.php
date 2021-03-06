<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 9/08/2019
 * Time: 08:40
 */

namespace App\Listener;

use Kookaburra\SystemAdmin\Entity\Setting;
use App\Provider\ProviderFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

/**
 * Class MailerFromAddress
 * @package App\Listener
 */
class MailerFromAddress implements EventSubscriberInterface
{
    /**
     * onMessageSend
     * @param MessageEvent $event
     */
    public function onMessageSend(MessageEvent $event)
    {
        $message = $event->getMessage();

        // make sure it's an Email object
        if (!$message instanceof Email) {
            return;
        }

        if (count($message->getFrom()) === 0) {
            $name = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'organisationName');
            $email = ProviderFactory::create(Setting::class)->getSettingByScopeAsString('System', 'organisationEmail');
            $address = new Address($email, $name);

            // always set the from address if no from address is set.
            $message->from($address);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            MessageEvent::class => 'onMessageSend',
        ];
    }
}
