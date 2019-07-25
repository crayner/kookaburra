<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 25/07/2019
 * Time: 09:38
 */

namespace App\Form\Entity;


class SystemSettings
{
    /**
     * @var null|string
     */
    private $title;

    /**
     * @var null|string
     */
    private $surname;

    /**
     * @var null|string
     */
    private $firstName;

    /**
     * @var null|string
     */
    private $email;

    /**
     * @var bool
     */
    private $support = true;

    /**
     * @var null|string
     */
    private $username;

    /**
     * @var null|string
     */
    private $password;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Title.
     *
     * @param string|null $title
     * @return SystemSettings
     */
    public function setTitle(?string $title): SystemSettings
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Surname.
     *
     * @param string|null $surname
     * @return SystemSettings
     */
    public function setSurname(?string $surname): SystemSettings
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * FirstName.
     *
     * @param string|null $firstName
     * @return SystemSettings
     */
    public function setFirstName(?string $firstName): SystemSettings
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Email.
     *
     * @param string|null $email
     * @return SystemSettings
     */
    public function setEmail(?string $email): SystemSettings
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSupport(): bool
    {
        return (bool) $this->support;
    }

    /**
     * Support.
     *
     * @param bool $support
     * @return SystemSettings
     */
    public function setSupport(bool $support): SystemSettings
    {
        $this->support = (bool) $support;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Username.
     *
     * @param string|null $username
     * @return SystemSettings
     */
    public function setUsername(?string $username): SystemSettings
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Password.
     *
     * @param string|null $password
     * @return SystemSettings
     */
    public function setPassword(?string $password): SystemSettings
    {
        $this->password = $password;
        return $this;
    }
}