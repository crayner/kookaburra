<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 10:04
 */

namespace App\Provider;

use App\Entity\I18n;
use App\Manager\Traits\EntityTrait;
use Gibbon\Contracts\Services\Session;

class I18nProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = I18n::class;

    /**
     * setLanguageSession
     * @param Session $session
     * @param bool $defaultLanguage
     * @throws \Exception
     */
    function setLanguageSession(Session $session, $defaultLanguage = true)
    {
        $result = $this->getRepository()->findOneBy(['systemDefault' => 'Y']);
        
        $data = [];
        $data['gibboni18nID'] = $result->getId();
        $data['code'] = $result->getCode();
        $data['name'] = $result->getName();
        $data['dateFormat'] = $result->getDateFormat();
        $data['dateFormatRegEx'] = $result->getDateFormatRegEx();
        $data['dateFormatPHP'] = $result->getDateFormatPHP();
        $data['rtl'] = $result->getRtl();

        if ($defaultLanguage) {
            $data['default']['code'] = $result->getCode();
            $data['default']['name'] = $result->getName();
        }
        
        $session->set('i18n', $data);
    }

}