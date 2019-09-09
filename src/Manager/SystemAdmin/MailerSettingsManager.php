<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 8/09/2019
 * Time: 16:29
 */

namespace App\Manager\SystemAdmin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

/**
 * Class MailerSettingsManager
 * @package App\Manager\SystemAdmin
 */
class MailerSettingsManager
{
    /**
     * handleMailerDsn
     * @param Request $request
     */
    public function handleMailerDsn(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        $config = Yaml::parse(file_get_contents(__DIR__.'/../../../config/packages/kookaburra.yaml'));

        $result = null;
        $setting = $content['emailSettings'];
        switch ($setting['System__enableMailerSMTP']) {
            case 'GMail':
                $result = 'smtp://'.$setting['System__mailerSMTPUsername'].':'.$setting['System__mailerSMTPPassword'].'@gmail';
                break;
            case 'No':
                break;
            default:
                $result = 'smtp://'.$setting['System__mailerSMTPUsername'].':'.$setting['System__mailerSMTPPassword'].'@'.$setting['System__mailerSMTPHost'].':'.$setting['System__mailerSMTPPort'].'?encryption='.$setting['System__mailerSMTPSecure'];
        }

        $config['parameters']['mailer_dns'] = $result;

        file_put_contents(__DIR__.'/../../../config/packages/kookaburra.yaml', Yaml::dump($config, 8));
    }
}