<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2020 Craig Rayner <craig@craigrayner.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * User: craig
 * Date: 1/01/2020
 * Time: 07:45
 */

namespace App\Manager\Traits;

use App\Util\ImageHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait ImageRemoval
 * @package App\Manager\Traits
 */
trait ImageRemoval
{
    /**
     * @var array
     */
    private $existingImage = [];

    /**
     * getExistingImage
     * @return string|null
     */
    public function getExistingImage(string $property): ?string
    {
        return $this->existingImage[$property];
    }

    /**
     * setExistingImage
     *
     * use $this->setExistingImage('getImage', '/build/static/DefaultLogo.png'); in your setImage method.
     *
     * @param string $method To get the current image.
     * @param string|null $default To ignore this default image.
     * @return $this
     */
    public function setExistingImage(string $property, ?string $default = null): self
    {
        $method = 'get' . ucfirst($property);
        if (method_exists($this, $method) && null !== $this->$method() && $default !== $this->$method())
            $this->existingImage[$property] = $this->$method();

        return $this;
    }

    /**
     * clearExistingImage
     * @return $this
     * @ORM\PostUpdate()
     */
    public function clearExistingImage(): self
    {
        foreach($this->existingImage as $property=>$value){
            ImageHelper::deleteImage($this->getExistingImage($property));
        }

        return $this;
    }

    /**
     * isFileInPublic
     * @param string|null $image
     * @return bool
     */
    public function isFileInPublic(?string $image): bool
    {
        return ImageHelper::isFileInPublic($image);
    }

    /**
     * removeFileOnDelete
     * @ORM\PostRemove()
     */
    public function removeFileOnDelete()
    {
        if (property_exists($this, 'filePropertyList'))
        {
            foreach($this->filePropertyList as $property) {
                $method = 'get' . ucfirst($property);
                if (method_exists($this, $method))
                    ImageHelper::deleteImage($this->$method());
            }
        }
    }
}