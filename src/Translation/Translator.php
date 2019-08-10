<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 9/08/2019
 * Time: 12:57
 */

namespace App\Translation;

use App\Entity\StringReplacement;
use App\Provider\ProviderFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface as TranslatorInterfaceLegacy;
use Symfony\Contracts\Translation\TranslatorInterface;

class Translator implements TranslatorInterfaceLegacy, TranslatorBagInterface, TranslatorInterface
{

    /**
     * {@inheritdoc}
     */
    public function trans($id, array $parameters = [], $domain = 'gibbon', $locale = null)
    {
        $id = $this->translator->trans($id, $parameters, $domain, $locale);

        return $this->getInstituteTranslation($id, $parameters);
    }

    /**
     * @param $trans
     * @return string
     */
    private function getInstituteTranslation($trans, array $parameters): string
    {
        if (empty($trans))
            return $trans;

        $strings = $this->getStrings();
        if ((! empty($strings) || $strings->count() > 0) && $strings instanceof ArrayCollection) {
            foreach ($strings->toArray() AS $replacement) {
                if ($replacement->getReplaceMode()=="partial") { //Partial match
                    if ($replacement->isCaseSensitive()=="Y") {
                        if (strpos($trans, $replacement->getOriginal())!==FALSE) {
                            $trans=str_replace($replacement->getOriginal(), $replacement->getReplacement(), $trans);
                        }
                    }
                    else {
                        if (stripos($trans, $replacement->getOriginal())!==FALSE) {
                            $trans=str_ireplace($replacement->getOriginal(), $replacement->getReplacement(), $trans);
                        }
                    }
                }
                else { //Whole match
                    if ($replacement->isCaseSensitive()=="Y") {
                        if ($replacement->getOriginal()==$trans) {
                            $trans=$replacement->getReplacement();
                        }
                    }
                    else {
                        if (strtolower($replacement->getOriginal())==strtolower($trans)) {
                            $trans=$replacement->getReplacement();
                        }
                    }
                }
            }
        }

        return str_replace(array_keys($parameters), array_values($parameters), $trans);
    }

    /**
     * @var null|Collection
     */
    private $strings;

    /**
     * getStrings
     *
     * @param bool $refresh
     * @return Collection|null
     * @throws \Exception
     */
    public function getStrings($refresh = false): ?Collection
    {
        $provider = ProviderFactory::create(StringReplacement::class);
        if (empty($this->strings) && ! $refresh)
            $this->strings = $provider->getSession()->get('stringReplacement', null);
        else
            return $this->strings;

        if ((empty($this->strings) || $refresh) && $provider->isValidEntityManager())
            try {
                $this->strings = new ArrayCollection($provider->getRepository()->findBy([], ['priority' => 'DESC', 'original' => 'ASC']));
            } catch (TableNotFoundException $e) {
                $this->strings = new ArrayCollection();
            }
        else
            return $this->strings = $this->strings instanceof ArrayCollection ? $this->strings : new ArrayCollection();

        $provider->getSession()->set('stringReplacement', $this->strings);

        return $this->strings;
    }

    /**
     * setStrings
     * @param Collection|null $strings
     * @return Translator
     */
    public function setStrings(?Collection $strings): Translator
    {
        if (empty($strings))
            $strings = new ArrayCollection();

        $this->strings = $strings;

        return $this;
    }
    /**
     * Translates the given choice message by choosing a translation according to a number.
     *
     * @param string      $id         The message id (may also be an object that can be cast to string)
     * @param int         $number     The number to use to find the indice of the message
     * @param array       $parameters An array of parameters for the message
     * @param string|null $domain     The domain for the message or null to use the default
     * @param string|null $locale     The locale or null to use the default
     *
     * @return string The translated string
     *
     * @throws InvalidArgumentException If the locale contains invalid characters
     * @deprecated Since Symfony 4.2  Use trans with a %count%
     */
    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null)
    {
        if (is_array($number))
            $trans = $this->multipleTransChoice($id, $number, $parameters, $domain, $locale);
        else
            $trans = $this->translator->transChoice($id, $number, $parameters, $domain, $locale);

        return $this->getInstituteTranslation($trans, $locale);
    }

    /**
     * Sets the current locale.
     *
     * @param string $locale The locale
     *
     * @throws InvalidArgumentException If the locale contains invalid characters
     */
    public function setLocale($locale)
    {
        return $this->translator->setLocale($locale);
    }

    /**
     * Returns the current locale.
     *
     * @return string The locale
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     * Gets the catalogue by locale.
     *
     * @param string|null $locale The locale or null to use the default
     *
     * @return MessageCatalogueInterface
     *
     * @throws InvalidArgumentException If the locale contains invalid characters
     */
    public function getCatalogue($locale = null)
    {
        return $this->translator->getCatalogue($locale);
    }

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Translator constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
}