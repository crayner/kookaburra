<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 7/09/2019
 * Time: 14:35
 */

namespace App\Manager\SystemAdmin;

use App\Entity\Setting;
use App\Provider\ProviderFactory;
use App\Provider\SettingProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

class GoogleSettingManager
{
    /**
     * @var SettingProvider
     */
    private $provider;

    /**
     * GoogleSettingManager constructor.
     * @param SettingProvider $provider
     */
    public function __construct()
    {
        $this->provider = ProviderFactory::create(Setting::class);
    }

    /**
     * handleGoogleSecretsFile
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return array
     */
    public function handleGoogleSecretsFile(FormInterface $form, Request $request, TranslatorInterface $translator)
    {
        $content = json_decode($request->getContent(), true);
        $file = new File($form->get('clientSecretFile')->getData(), true);
        $secret = json_decode(file_get_contents($file->getRealPath()), true);
        unlink($file->getRealPath());
        $config = Yaml::parse(file_get_contents(__DIR__.'/../../../config/packages/kookaburra.yaml'));

        $config['parameters']['google_api_key'] = $content['googleSettings']['System__googleDeveloperKey'];
        $config['parameters']['google_client_id'] = $secret['web']['client_id'];
        $config['parameters']['google_client_secret'] = $secret['web']['client_secret'];

        file_put_contents(__DIR__.'/../../../config/packages/kookaburra.yaml', Yaml::dump($config, 8));
    }
}