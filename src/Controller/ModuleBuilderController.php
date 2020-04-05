<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 21/12/2019
 * Time: 08:29
 */

namespace App\Controller;

use App\Provider\ProviderFactory;
use App\Util\StringHelper;
use Kookaburra\SystemAdmin\Entity\I18n;
use Kookaburra\SystemAdmin\Entity\Module;
use Kookaburra\SystemAdmin\Entity\NotificationEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\Loader\MoFileLoader;
use Symfony\Component\Translation\MessageCatalogue;
use Symfony\Component\Yaml\Yaml;

/**
 * Class ModuleBuilderController
 * @package App\Controller
 */
class ModuleBuilderController extends AbstractController
{
    /**
     * Module Builder
     * @Route("/module/action/build/", name="module_action_build")
     * @param ParameterBagInterface $bag
     */
    public function build(ParameterBagInterface $bag)
    {
        $module = ProviderFactory::create(Module::class)->findOneBy(['name' => 'System Admin']); //School Admin
        dump('Change the search detail here to map a module/actions/permissions. Currently ' . $module->getName());
        $result = [];
        $x['name'] = $module->getName();
        $x['description'] = $module->getDescription();
        $x['entryURL'] = $module->getEntryURL();
        $x['type'] = $module->getType();
        $x['active'] = $module->getActive();
        $x['category'] = $module->getCategory();
        $x['version'] = $module->getVersion();
        $x['author'] = $module->getAuthor();
        $x['url'] = $module->getUrl();
        $result['version'] = '0.0.00';
        $result['name'] = $module->getName();;
        $result['module'] = $x;

        foreach($module->getActions() as $action)
        {
            $x = [];
            $x['name'] = $action->getName();
            $x['precedence'] = $action->getPrecedence();
            $x['category'] = $action->getCategory();
            $x['description'] = $action->getDescription();
            $x['URLList'] = $action->getURLList();
            $x['entryURL'] = $action->getEntryURL();
            $x['entrySidebar'] = $action->getEntrySidebar();
            $x['menuShow'] = $action->getMenuShow();
            $x['defaultPermissionAdmin'] = $action->getDefaultPermissionAdmin();
            $x['defaultPermissionTeacher'] = $action->getDefaultPermissionTeacher();
            $x['defaultPermissionStudent'] = $action->getDefaultPermissionStudent();
            $x['defaultPermissionParent'] = $action->getDefaultPermissionParent();
            $x['defaultPermissionSupport'] = $action->getDefaultPermissionSupport();
            $x['categoryPermissionStaff'] = $action->getCategoryPermissionStaff();
            $x['categoryPermissionStudent'] = $action->getCategoryPermissionStudent();
            $x['categoryPermissionParent'] = $action->getCategoryPermissionParent();
            $x['categoryPermissionOther'] = $action->getCategoryPermissionOther();


            foreach($action->getPermissions() as $permission) {
                $x['permissions'][] = $permission->getRole()->getName();
            }
            $result['module']['actions'][$action->getName()] = $x;
        }
        $publicDir = $bag->get('kernel.public_dir');


        // Notifications
        $provider = ProviderFactory::create(NotificationEvent::class);
/*        $notificationEvents = $provider->getRepository()->findBy([], ['moduleName' => 'ASC', 'actionName' => 'ASC', 'event' => 'ASC']);

        $connection = $provider->getEntityManager()->getConnection();
        $connection->beginTransaction();

        $connection->query('DELETE FROM `gibbonNotificationEvent`');
        $connection->query('ALTER TABLE `gibbonNotificationEvent` AUTO_INCREMENT = 1');
        $connection->commit();
        $provider->getEntityManager()->clear();

        foreach($notificationEvents as $q=>$event)
        {
            $mod = ProviderFactory::getRepository(Module::class)->findOneBy(['name' => $event->getModuleName()]);
            $action = null;
            foreach($mod->getActions()->toArray() as $w)
            {
                if ($w->getName() === $event->getActionName())
                {
                    $action = $w;
                    break;
                }
            }
            $event->setId(null)->setAction($action)->setModule($mod)->setModuleName(null)->setActionName(null);
        }

        foreach($notificationEvents as $event) {
            $provider->getEntityManager()->persist($event);
            $provider->getEntityManager()->flush();
        }
*/
        $notificationEvents = $provider->getRepository()->findBy(['module' => $module], ['moduleName' => 'ASC', 'actionName' => 'ASC', 'event' => 'ASC']);

        $events = [];
        foreach($notificationEvents as $w)
        {
            $event = [];
            $event['event'] = $w->getEvent();
            $event['module'] = $w->getModuleName();
            $event['action'] = $w->getActionName();
            $event['scopes'] = $w->getScopes();
            $event['active'] = $w->isActive() ? 'Y' : 'N';
            $events[$w->getEvent()] = $event;
        }

        $result['events'] = $events;

        file_put_contents($publicDir . '/' . $module->getName() . '.yaml', Yaml::dump($result, 8));

        dd(Yaml::dump($result, 8));
    }

    /**
     * Module Builder
     * @Route("/translation/install/", name="translation_install")
     * @IsGranted("ROLE_SYSTEM_ADMIN")
     */
    public function translationsMerge()
    {
        foreach(ProviderFactory::getRepository(I18n::class)->findBy(['active' => 'Y']) as $i18n)
            $this->translationBuild($i18n->getCode());
        dd($this);
    }

    /**
     * translationBuild
     * @param string $targetCode
     */
    public function translationBuild(string $targetCode)
    {
        if ($targetCode === 'en_GB')
            return ;
        $gitHubURL = 'https://github.com/GibbonEdu/i18n/blob/master/'.$targetCode.'/LC_MESSAGES/gibbon.mo?raw=true';
        $content = file_get_contents($gitHubURL);

        $path = realpath('../translations') . '/messages.'.$targetCode.'.mo';
        file_put_contents($path, $content);

        $messagesPath = realpath('../translations') . '/messages+intl-icu.'.$targetCode.'.yaml';
        $en = Yaml::parse(file_get_contents('../translations/messages+intl-icu.en_GB.yaml'));
        if (!file_exists($messagesPath)) {
            file_put_contents($messagesPath, Yaml::dump($en, 8));
        }

        $messagesPath = realpath($messagesPath);

        $source = new MoFileLoader();

        $catalogue = $source->load($path, $targetCode, 'messages');

        $targetCatalogue = Yaml::parse(file_get_contents($messagesPath));

        $targetCatalogue = array_merge($en, $targetCatalogue); // Add new translation string

        foreach($targetCatalogue as $id=>$value)
        {
            if (is_string($value))
                $targetCatalogue[$id] = $this->translateString($id, $value, $catalogue);

            if (is_array($value))
                $targetCatalogue[$id] = $this->translateArray($value, $catalogue);

        }

        $diff = array_diff_key($targetCatalogue, $en);
        foreach($diff as $key=>$value)
            unset($targetCatalogue[$key]);

        file_put_contents($messagesPath, Yaml::dump($targetCatalogue, 8, 4));

        $finder = new Finder();

        $bundles = $finder->files()->in('../vendor/kookaburra/*/src/Resources/translations/')->name('*+intl-icu.en_GB.yaml')->depth(0);
        foreach($bundles as $bundle) {

            $en = Yaml::parse(file_get_contents($bundle->getPathName()));
            $target = str_replace('en_GB', $targetCode, $bundle->getPathName());

            if (!file_exists($target)) {
                file_put_contents($target, Yaml::dump($en, 8, 4));
                $targetCatalogue = $en;
            } else
                $targetCatalogue = array_merge($en, Yaml::parse(file_get_contents($target)));

            foreach($targetCatalogue as $id=>$value)
            {
                if (is_string($value))
                    $targetCatalogue[$id] = $this->translateString($id, $value, $catalogue);

                if (is_array($value))
                    $targetCatalogue[$id] = $this->translateArray($value, $catalogue);

            }

            $diff = array_diff_key($targetCatalogue, $en);
            foreach($diff as $key=>$value)
                unset($targetCatalogue[$key]);

            file_put_contents($target, Yaml::dump($targetCatalogue, 8, 4));
        }

        $finder = new Finder();
        $bundles = $finder->files()->in('../translations/')->name(['*.mo', '*.po'])->depth(0);
        foreach($bundles as $bundle) {
            unlink($bundle->getPathName());
        }
        dump($targetCode);
    }

    /**
     * translateArray
     * @param array $group
     * @param MessageCatalogue $catalogue
     * @return array
     */
    private function translateArray(array $group, MessageCatalogue $catalogue): array
    {
        foreach($group as $q=>$w)
        {
            if (is_array($w)) {
                $group[$q] = $this->translateArray($w, $catalogue);
            } else {
                $group[$q] = $this->translateString(null,$w,$catalogue);
            }
        }
        return $group;
    }

    /**
     * translateString
     * @param string $id
     * @param string $value
     * @param MessageCatalogue $catalogue
     * @return string
     */
    private function translateString(?string $id, string $value, MessageCatalogue $catalogue): string
    {
        if ($id !== null) {
            $translated = $catalogue->get($id, 'messages');
            if ($translated !== $value) {
                return $translated;
            }
        }
        $translated = $catalogue->get($value, 'messages');
        if ($translated !== $value)
        {
            return $translated;
        }
        return $value;
    }
}