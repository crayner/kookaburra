<?php
/**
 * Created by PhpStorm.
 *
 * Kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 21/02/2020
 * Time: 08:52
 */

namespace App\Manager\Entity;

use App\Twig\MainMenu;
use App\Util\ImageHelper;
use App\Util\TranslationsHelper;
use App\Util\UrlGeneratorHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class HeaderManager
 * @package App\Manager\Entity
 */
class HeaderManager
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    /**
     * @var MainMenu
     */
    private $mainMenu;

    /**
     * HeaderManager constructor.
     * @param Request $request
     * @param AuthorizationCheckerInterface $checker
     */
    public function __construct(Request $request, AuthorizationCheckerInterface $checker, MainMenu $mainMenu)
    {
        $this->request = $request;
        $this->checker = $checker;
        $this->mainMenu = $mainMenu;
    }

    /**
     * toArray
     * @return array
     */
    public function toArray(): array
    {
        $this->setTranslations();
        return [
            'homeURL' => UrlGeneratorHelper::getUrl('home'),
            'organisationName' => $this->getRequest()->getSession()->get('organisationName', 'Kookaburra'),
            'organisationLogo' => ImageHelper::getLogoImage(),
            'menu' => $this->getMainMenu(),
            'translations' => TranslationsHelper::getTranslations(),
        ];
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @param $attributes
     * @param null $subject
     * @return bool
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->checker->isGranted($attributes, $subject);
    }

    /**
     * getMainMenu
     * @return array
     */
    private function getMainMenu(): array
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY'))
            return [];
        $this->mainMenu->execute();
        if ($this->mainMenu->isValid() && $this->mainMenu->hasAttribute('menuMainItems'))
        {
            $result = $this->mainMenu->getAttribute('menuMainItems');
            foreach($result as $catName=>$items)
            {
                TranslationsHelper::addTranslation($catName);
                foreach($items as $q=>$item)
                {
                    TranslationsHelper::addTranslation($item['name'], [], 'messages');
                    if ($item['route'] !== false)
                        $result[$catName][$q]['url'] = UrlGeneratorHelper::getUrl($item['route'], []);
                }
            }
            return $result;
        }
        return [];
    }

    /**
     * setTranslations
     * @return $this
     */
    private function setTranslations(): self
    {
        TranslationsHelper::addTranslation('Home', [], 'messages');
        TranslationsHelper::addTranslation('Kookaburra', [], 'messages');
        return $this;
    }
}