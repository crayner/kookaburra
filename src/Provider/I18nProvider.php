<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 1/07/2019
 * Time: 10:04
 */

namespace App\Provider;

use App\Entity\I18n;
use App\Manager\Traits\EntityTrait;
use App\Util\GlobalHelper;
use Gibbon\Contracts\Services\Session;
use Kookaburra\UserAdmin\Util\UserHelper;

class I18nProvider implements EntityProviderInterface
{
    use EntityTrait;

    /**
     * @var string
     */
    private $entityName = I18n::class;

    /**
     * @var string|null
     */
    private $datePHPFormat;

    /**
     * setLanguageSession
     * @param Session $session
     * @param bool $defaultLanguage
     * @throws \Exception
     */
    public function setLanguageSession(Session $session, array $criteria = ['systemDefault' => 'Y'], $defaultLanguage = true)
    {
        $result = $this->getRepository()->findOneBy($criteria);
        if (!$result instanceof I18n)
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

    /**
     * selectI18n
     * @return array
     * @throws \Exception
     */
    public function selectI18n(): array
    {
        $result = [];
        foreach($this->findBy(['active' => 'Y'],['code' => 'ASC']) as $i18n)
            if ($i18n->isInstalled())
                $result[$i18n->getName()] = $i18n->getId();

        return $result;
    }

    /**
     * getDatePHPFormat
     */
    public function getDatePHPFormat()
    {
        if (null === $this->datePHPFormat)
        {
            $person = UserHelper::getCurrentUser();
            $i18n = $person->getI18nPersonal() ?: $this->getRepository()->findOneBy(['code' => GlobalHelper::hasParam('locale') ? GlobalHelper::getParam('locale', 'en_GB') : 'en_GB']);
            $this->datePHPFormat = $i18n ? $i18n->getDateFormatPHP() : $this->getRepository()->findOneBy(['code' => 'en_GB'])->getDateFormatPHP();
        }

        return $this->datePHPFormat ?: 'd M/Y';
    }

    /**
     * getSelectedLanguages
     * @return array
     */
    public function getSelectedLanguages(): array
    {
        $result = [];
        foreach($this->getRepository()->findByActive() as $lang)
            $result[$lang->getName()] = $lang->getId();
        return $result;
    }

}