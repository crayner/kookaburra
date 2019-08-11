<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 27/07/2019
 * Time: 16:43
 */
namespace App\Twig;

use App\Security\SecurityUser;
use App\Util\UserHelper;
use Doctrine\Common\Collections\ArrayCollection;

class Sidebar implements ContentInterface
{
    use ContentTrait;

    /**
     * execute
     */
    public function execute(): void
    {
        $user = UserHelper::getSecurityUser();
        $this->content = false;

        if ($user instanceof SecurityUser) {
            // Show role switcher if user has more than one role
            if (count($user->getAllRolesAsArray()) > 1 && false === $this->getRequest()->get('address')) {

                $this->addAttribute('role_switcher', true);
            }
        } else {

        }
    }

    /**
     * @var ArrayCollection|SidebarExtra[]
     */
    private $extras;

    /**
     * @return SidebarExtra[]|ArrayCollection
     */
    public function getExtras(string $position = 'all')
    {
        if (null === $this->extras)
            $this->extras = new ArrayCollection();

        if (in_array($position, ['top','bottom'])) {
            return $this->extras->filter(
                function ($entry) use ($position) {
                    return $entry->getPosition() === $position;
                }
            );
        }
        return $this->extras;
    }

    /**
     * Extras.
     *
     * @param SidebarExtra[]|ArrayCollection $extra
     * @return Sidebar
     */
    public function setExtras(ArrayCollection $extras)
    {
        $this->extras = $extras;
        return $this;
    }

    /**
     * addExtra
     * @param string $type
     * @param array $content
     * @return SidebarExtra
     * @throws \Gibbon\Exception
     */
    public function addExtra(string $type, array $content): SidebarExtra
    {
        $extra = new SidebarExtra($type, $content);
        $this->getExtras()->add($extra);

        return $extra->setPriority($this->getExtras()->count());
    }

    /**
     * isValid
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->getValid() && (count($this->getExtras()) > 0 || count($this->getAttributes()) > 0);
    }
}