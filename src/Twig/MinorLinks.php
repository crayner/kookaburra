<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 29/07/2019
 * Time: 14:32
 */

namespace App\Twig;


use App\Util\SecurityHelper;
use App\Util\UserHelper;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MinorLinks implements ContentInterface
{
    use ContentTrait;

    /**
     * execute
     * @throws \Exception
     */
    public function execute(): void
    {
        $return = '';
        // Add a link to go back to the system/personal default language, if we're not using it
        if ($this->getSession()->has(['i18n','default','code']) && $this->getSession()->has(['i18n','code'])) {
            if ($this->getSession()->get(['i18n','code']) !== $this->getSession()->get(['i18n','default','code'])) {
                $systemDefaultShortName = trim(strstr($this->getSession()->get(['i18n','default','name']), '-', true));
                $languageLink = "<a class='link-white' href='".$this->getSession()->get('absoluteURL')."?i18n=".$this->getSession()->get(['i18n','default','code'])."'>".$systemDefaultShortName.'</a>';
            }
        }

        if (!SecurityHelper::isGranted('IS_AUTHENTICATED_FULLY')) {
            $return .= !empty($languageLink) ? $languageLink : '';

            if ($this->getSession()->get('webLink') !== '') {
                $return .= !empty($languageLink) ? ' . ' : '';
                $return .= $this->getTranslator()->trans('Return to', [], 'gibbon')." <a class='link-white' style='margin-right: 12px' target='_blank' href='".$this->getSession()->get('webLink')."'>".$this->getSession()->get('organisationNameShort').' '.$this->getTranslator()->trans('Website', [], 'gibbon').'</a>';
            }
        } else {
            $name = $this->getSession()->get('preferredName').' '.$this->getSession()->get('surname');
            if ($this->getSession()->has('gibbonRoleIDCurrentCategory')) {
                if ($this->getSession()->get('gibbonRoleIDCurrentCategory') === 'Student') {
                    $highestAction = SecurityHelper::getHighestGroupedAction('/modules/Students/student_view_details.php');
                    if ($highestAction == 'View Student Profile_brief') {
                        $name = "<a class='link-white' href='".$this->getSession()->get('absoluteURL').'/index.php?q=/modules/Students/student_view_details.php&gibbonPersonID='.$this->getSession()->get('gibbonPersonID')."'>".$name.'</a>';
                    }
                }
            }

            $return .= $name.' . ';
            $return .= "<a class='link-white' href='".$this->getRouter()->generate('logout')."'>".$this->getTranslator()->trans('Logout', [],'gibbon')."</a> . <a class='link-white' href='".$this->getRouter()->generate('preferences')."'>".$this->getTranslator()->trans('Preferences', [],'gibbon').'</a>';
            if ($this->getSession()->get('emailLink') !== '') {
                $return .= "<span class='hidden sm:inline'> . <a class='link-white' target='_blank' href='".$this->getSession()->get('emailLink')."'>".$this->getTranslator()->trans('Email', [], 'gibbon').'</a></span>';
            }
            if ($this->getSession()->get('webLink') !== '') {
                $return .= "<span class='hidden sm:inline'>  . <a class='link-white' target='_blank' href='".$this->getSession()->get('webLink')."'>".$this->getSession()->get('organisationNameShort').' '.$this->getTranslator()->trans('Website', [],'gibbon').'</a></span>';
            }
            if ($this->getSession()->get('website') !== '') {
                $return .= "<span class='hidden sm:inline'>  . <a class='link-white' target='_blank' href='".$this->getSession()->get('website')."'>".$this->getTranslator()->trans('My Website', [],'gibbon').'</a></span>';
            }

            $return .= !empty($languageLink) ? ' . '.$languageLink : '';

            //Check for house logo (needed to get bubble, below, in right spot)
            if ($this->getSession()->has('gibbonHouseIDLogo') and $this->getSession()->has('gibbonHouseIDName')) {
                if ($this->getSession()->get('gibbonHouseIDLogo') !== '') {
                    $return .= " . <img class='ml-1 w-10 h-10 sm:w-12 sm:h-12 lg:w-16 lg:h-16' title='".$this->getSession()->get('gibbonHouseIDName')."' style='vertical-align: -75%;' src='".$this->getSession()->get('absoluteURL').'/'.$this->getSession()->get('gibbonHouseIDLogo')."'/>";
                }
            }
        }

        $this->addAttribute('minorLinks', $return);
    }

    /**
     * @var TranslatorInterface
     */
    private $translator;

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
     * @return MinorLinks
     */
    public function setTranslator(TranslatorInterface $translator): MinorLinks
    {
        $this->translator = $translator;
        return $this;
    }

    /**
     * @var RouterInterface
     */
    private $router;

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
     * @return MinorLinks
     */
    public function setRouter(RouterInterface $router): MinorLinks
    {
        $this->router = $router;
        return $this;
    }
}