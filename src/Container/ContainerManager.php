<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 19/08/2019
 * Time: 11:19
 */

namespace App\Container;


use App\Manager\ScriptManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContainerManager
{
    /**
     * @var ScriptManager
     */
    private $scriptManager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var null|string
     */
    private $translationDomain;

    /**
     * @var bool
     */
    private $globalForm = false;

    /**
     * ContainerManager constructor.
     * @param ScriptManager $scriptManager
     */
    public function __construct(ScriptManager $scriptManager, TranslatorInterface $translator)
    {
        $this->scriptManager = $scriptManager;
        $this->translator = $translator;
    }

    /**
     * @var ArrayCollection
     */
    private $containers;

    /**
     * @return ArrayCollection
     */
    public function getContainers(): ArrayCollection
    {
        if (null === $this->containers)
            $this->containers = new ArrayCollection();

        return $this->containers;
    }

    /**
     * Containers.
     *
     * @param ArrayCollection $containers
     * @return ContainerManager
     */
    public function setContainers(ArrayCollection $containers): ContainerManager
    {
        $this->containers = $containers;
        return $this;
    }

    /**
     * addContainer
     * @param Container $container
     * @return ContainerManager
     */
    public function addContainer(Container $container): ContainerManager
    {
        if (null === $container->getTranslationDomain())
            $container->setTranslationDomain($this->getTranslationDomain());

        $container = $this->resolveContainer($container);

        $this->getContainers()->set($container->getTarget(), $container->toArray($this->translator));

        return $this;
    }

    /**
     * resolveContainer
     * @param Container $container
     * @return Container
     */
    private function resolveContainer(Container $container): Container
    {
        $resolver = new OptionsResolver();

        $resolver->setRequired(
            [
                'target',
            ]
        );
        $resolver->setDefaults(
            [
                'content' => null,
                'panels' => null,
                'translationDomain' => null,
                'selectedPanel' => null,
            ]
        );

        $resolver->setAllowedTypes('target', 'string');
        $resolver->setAllowedTypes('content', ['string', 'null']);
        $resolver->setAllowedTypes('selectedPanel', ['string', 'null']);
        $resolver->setAllowedTypes('translationDomain', ['string', 'null']);
        $resolver->setAllowedTypes('panels', [ArrayCollection::class, 'null']);

        $resolver->resolve($container->toArray());

        if ('' === $container->getTarget())
            throw new \InvalidArgumentException(sprintf('The container target is empty!'));

        return $container;
    }

    /**
     * buildContainers
     * @return ContainerManager
     */
    public function buildContainers(): ContainerManager
    {
        $containers = [];
        foreach($this->getContainers() as $target=>$container) {
            foreach($container['panels'] as $q=>$panel) {
                $panel['label'] = $this->translator->trans($panel['name'], [], $this->getTranslationDomain($panel));
                $container['panels'][$q] = $panel;
            }
            $container['panels'] = $container['panels']->toArray();
            $container['globalForm'] = $this->isGlobalForm();
            $container['form'] = $this->isGlobalForm();
            if ($this->isGlobalForm() || true)
            {
                $panel = reset($container['panels']);
                $container['form'] = $panel['form'];
            }

            $containers[$target] = $container;
        }

        dump($containers);
        $this->scriptManager->addAppProp('container', $containers);
        $this->scriptManager->addEncoreEntryCSSFile('container');
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTranslationDomain(array $trans = []): ?string
    {
        return !isset($trans['translationDomain']) || is_null($trans['translationDomain']) ? $this->translationDomain : $trans['translationDomain'];
    }

    /**
     * TranslationDomain.
     *
     * @param string $translationDomain
     * @return ContainerManager
     */
    public function setTranslationDomain(?string $translationDomain): ContainerManager
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }

    /**
     * DefaultPanel.
     *
     * @param string|null $defaultPanel
     * @return ContainerManager
     */
    public function setDefaultPanel(?string $defaultPanel): ContainerManager
    {
        $this->defaultPanel = $defaultPanel;
        return $this;
    }

    /**
     * @return bool
     */
    public function isGlobalForm(): bool
    {
        return $this->globalForm;
    }

    /**
     * GlobalForm.
     *
     * @param bool $globalForm
     * @return ContainerManager
     */
    public function setGlobalForm(bool $globalForm): ContainerManager
    {
        $this->globalForm = $globalForm;
        return $this;
    }
}