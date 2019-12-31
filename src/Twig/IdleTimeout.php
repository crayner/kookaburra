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
 * Date: 29/07/2019
 * Time: 13:43
 */

namespace App\Twig;

use App\Entity\Setting;
use App\Manager\ScriptManager;
use App\Provider\ProviderFactory;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class IdleTimeout
 * @package App\Twig
 */
class IdleTimeout implements ContentInterface
{
    use ContentTrait;

    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * execute
     */
    public function execute(): void
    {
        $data['route'] = $this->getRouter()->generate('logout', ['timeout' => 'true']);
        $data['duration'] = ProviderFactory::create(Setting::class)->getSettingByScopeAsInteger('System', 'sessionDuration', 1200);
        $data['trans_sessionExpire'] = $this->translate('Your session is about to expire: you will be logged out shortly.');
        $data['trans_stayConnected'] = $this->translate('Stay Connected');

        $this->getScriptManager()->addAppProp('idleTimeout', $data);
    }

    /**
     * @return ScriptManager
     */
    public function getScriptManager(): ScriptManager
    {
        return $this->scriptManager;
    }

    /**
     * ScriptManager.
     *
     * @param ScriptManager $scriptManager
     * @return IdleTimeout
     */
    public function setScriptManager(ScriptManager $scriptManager): IdleTimeout
    {
        $this->scriptManager = $scriptManager;
        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * Router.
     *
     * @param RouterInterface $router
     * @return IdleTimeout
     */
    public function setRouter(RouterInterface $router): IdleTimeout
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @return TranslatorInterface
     */
    public function getTranslator(): TranslatorInterface
    {
        return $this->translator;
    }

    /**
     * Translator.
     *
     * @param TranslatorInterface $translator
     * @return IdleTimeout
     */
    public function setTranslator(TranslatorInterface $translator): IdleTimeout
    {
        $this->translator = $translator;
        return $this;
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
}