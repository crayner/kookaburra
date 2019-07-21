<?php
/**
 * Created by PhpStorm.
 *
 * bilby
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 20/07/2019
 * Time: 16:40
 */

namespace App\Manager\SystemAdmin;

use App\Entity\I18n;
use App\Provider\ProviderFactory;

class LanguageManager
{

    /**
     * i18nFileInstall
     *
     * Downloads and installs the gibbon.mo file for a given i18n code.
     * @param string $absolutePath
     * @param I18n $code
     * @return bool
     */
    public function i18nFileInstall(string $absolutePath, I18n $i18n)
    {
        // Grab the file contents from the GibbonEdu i18n repository
        $gitHubURL = 'https://github.com/GibbonEdu/i18n/blob/master/'.$i18n->getCode().'/LC_MESSAGES/gibbon.mo?raw=true';
        $gitHubContents = file_get_contents($gitHubURL);

        if (empty($gitHubContents)) return false;

        // Locate where the i18n files will be copied to on the server
        $localPath = $absolutePath.'/translations/gibbon.'.$i18n->getCode().'.mo';
        $localDir = dirname($localPath);
        if (!is_dir($localDir)) {
            mkdir($localDir, 0755, true);
        }

        // Copy files
        return file_put_contents($localPath, $gitHubContents) !== false;
    }

    /**
     * Checks to see if a gibbon.mo language file exists for the given i18n code.
     *
     * @param string $absolutePath
     * @param string $code
     * @return bool
     */
    public function i18nFileExists($absolutePath, $code)
    {
        return file_exists($absolutePath.'/translations/gibbon.'.$code.'.mo');
    }

    /**
     * Finds and sets any languages to installed='Y' if the file already exists.
     * Sets langueges to  installed='N' if the file no longer exits.
     *
     */
    public function i18nCheckAndUpdateVersion(string $absolutePath, $version = null)
    {
        $provider = ProviderFactory::create(I18n::class);
        $i18nList = $provider->getRepository(I18n::class)->findByActive('Y');

        foreach ($i18nList as $i18n) {
            $fileExists = $this->i18nFileExists($absolutePath, $i18n->getCode());

            if (! $i18n->isInstalled() && $fileExists) {
                $versionUpdate = version_compare($version, $i18n->getVersion(), '>') ? $version : $i18n->getVersion();
                $i18n->setVersion($versionUpdate);
                $i18n->setInstalled('Y');
                $provider->setEntity($i18n)->saveEntity();
            } elseif ($i18n->isInstalled() && !$fileExists) {
                $i18n->setVersion(null);
                $i18n->setInstalled('N');
                $provider->setEntity($i18n)->saveEntity();
            }
        }
    }
}