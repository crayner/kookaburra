<?php
/**
 * Created by PhpStorm.
 *
 * Gibbon-Responsive
 *
 * (c) 2018 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * UserProvider: craig
 * Date: 23/11/2018
 * Time: 11:58
 */
namespace App\Repository;

use App\Entity\I18n;
use App\Util\LocaleHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class I18nRepository
 * @package App\Repository
 */
class I18nRepository extends ServiceEntityRepository
{
    /**
     * @var string|null
     */
    private $locale;

    /**
     * ApplicationFormRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, I18n::class);
    }

    /**
     * findSystemDefaultCode
     * @return string|null
     */
    public function findSystemDefaultCode(): ?string
    {
        $systemDefault = $this->findOneBySystemDefault('Y');
        return $systemDefault ? $systemDefault->getCode() : null;
    }

    /**
     * findLocaleRightToLeft
     * @return bool
     * @throws \Exception
     */
    public function findLocaleRightToLeft(): bool
    {
        if (null === $this->locale)
            $this->locale = LocaleHelper::getLocale();

        $lang = $this->findOneByCode($this->locale);

        return $lang ? $lang->isRtl() : false;
    }

    /**
     * findByActive
     * @return array
     */
    public function findByActive(): array
    {
        return $this->createQueryBuilder('i')
            ->where('i.active = :yes')
            ->andWhere('i.installed = :yes')
            ->orWhere('i.systemDefault = :yes')
            ->orderby('i.systemDefault', 'DESC')
            ->addOrderBy('i.name', 'ASC')
            ->setParameter('yes', 'Y')
            ->getQuery()
            ->getResult();
    }
}
