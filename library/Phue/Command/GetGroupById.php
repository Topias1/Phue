<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Command;

use Phue\Client;
use Phue\Group;

/**
 * Get group by id command
 */
class GetGroupById implements CommandInterface
{
    /**
     * Group Id
     *
     * @var int
     */
    protected int $groupId;

    /**
     * Constructs a command
     *
     * @param int $groupId Group Id
     */
    public function __construct(int $groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return Group Group object
     */
    public function send(Client $client): Group
    {
        // Get response
        $attributes = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/groups/{$this->groupId}"
        );
        
        return new Group($this->groupId, $attributes, $client);
    }
}