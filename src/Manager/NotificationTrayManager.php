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
 * Date: 6/08/2019
 * Time: 07:56
 */

namespace App\Manager;

use Kookaburra\SystemAdmin\Entity\Notification;
use App\Provider\ProviderFactory;
use Kookaburra\UserAdmin\Manager\SecurityUser;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class NotificationTrayManager
{

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * NotificationTrayManager constructor.
     * @param RouterInterface $router
     * @param TranslatorInterface $translator
     */
    public function __construct(RouterInterface $router, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->translator = $translator;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * translate
     * @param string $key
     * @param array|null $params
     * @param string|null $domain
     * @return string
     */
    private function translate(string $key, ?array $params = [], ?string $domain = 'messages'): string
    {
        return $this->getTranslator()->trans($key, $params, $domain);
    }

    /**
     * execute
     * @param $user
     * @return array
     */
    public function execute($user): array
    {
        $notifications = [];
        if ($user instanceof SecurityUser)
            $notifications = ProviderFactory::getRepository(Notification::class)->findByPersonStatus($user->getPerson());

        if (count($notifications) === 0)
            return [
                'class' => 'inactive inline-block relative mr-4',
                'title' => $this->translate('Notifications'),
                'url' => $this->router->generate('notifications_manage'),
                'count' => 0,
            ];
        return [
            'class' => 'inline-block relative mr-4',
            'title' => $this->translate('Notifications'),
            'url' => $this->router->generate('notifications_manage'),
            'count' => count($notifications),
        ];
    }
}