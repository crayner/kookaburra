<?php
/**
 * Created by PhpStorm.
 *
 * kookaburra
 * (c) 2019 Craig Rayner <craig@craigrayner.com>
 *
 * User: craig
 * Date: 14/10/2019
 * Time: 16:59
 */

namespace App\Manager\Entity;


class Message
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $level;


    /**
     * @var string
     */
    private $domain = 'messages';

    /**
     * @var array
     */
    private $options = [];

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Message.
     *
     * @param string $message
     * @return Message
     */
    public function setMessage(string $message): Message
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Level.
     *
     * @param string $level
     * @return Message
     */
    public function setLevel(string $level): Message
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * Domain.
     *
     * @param string $domain
     * @return Message
     */
    public function setDomain(string $domain): Message
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * getOptions
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options = $this->options ?: [];
    }

    /**
     * Options.
     *
     * @param array $options
     * @return Message
     */
    public function setOptions(array $options): Message
    {
        $this->options = $options;
        return $this;
    }

    /**
     * addOption
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addOption(string $name, string $value): Message
    {
        $this->getOptions();
        $this->options[$name] = $value;
        return $this;
    }
}