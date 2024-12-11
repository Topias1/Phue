<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue;

use Phue\Command\DeleteUser;

/**
 * User object
 */
class User
{
    /**
     * Username
     *
     * @var string
     */
    protected string $username;

    /**
     * Attributes
     *
     * @var \stdClass|null
     */
    protected ?\stdClass $attributes;

    /**
     * Phue client
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Construct a User object
     *
     * @param string $username
     *            Username
     * @param \stdClass|null $attributes
     *            User attributes
     * @param Client $client
     *            Phue client
     */
    public function __construct(string $username, ?\stdClass $attributes, Client $client)
    {
        $this->username = $username;
        $this->attributes = $attributes;
        $this->client = $client;
    }

    /**
     * Get username
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get device type
     *
     * @return string Device type
     */
    public function getDeviceType(): string
    {
        return $this->attributes->name ?? 'Unknown';
    }

    /**
     * Get create date
     *
     * @return string Create date
     */
    public function getCreateDate(): string
    {
        return $this->attributes->{'create date'} ?? 'Unknown';
    }

    /**
     * Get last use date
     *
     * @return string Last use date
     */
    public function getLastUseDate(): string
    {
        return $this->attributes->{'last use date'} ?? 'Unknown';
    }

    /**
     * Delete user
     */
    public function delete(): void
    {
        $this->client->sendCommand(new DeleteUser($this));
    }

    /**
     * __toString
     *
     * @return string Username
     */
    public function __toString(): string
    {
        return $this->username;
    }
}