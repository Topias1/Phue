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
use Phue\TimePattern\AbsoluteTime;
use Phue\TimePattern\TimePatternInterface;
use Phue\Transport\TransportInterface;

/**
 * Create schedule command
 */
class CreateSchedule implements CommandInterface
{
    /**
     * Schedule attributes
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Command
     *
     * @var ActionableInterface|null
     */
    protected ?ActionableInterface $command = null;

    /**
     * Time pattern
     *
     * @var TimePatternInterface|null
     */
    protected ?TimePatternInterface $time = null;

    /**
     * Constructs a create schedule command
     *
     * @param string|null $name
     *            Name of schedule
     * @param mixed $time
     *            Time to run command
     * @param ActionableInterface|null $command
     *            Actionable command
     */
    public function __construct(
        ?string $name = null,
        $time = null,
        ?ActionableInterface $command = null
    ) {
        if ($name !== null) {
            $this->name($name);
        }
        if ($time !== null) {
            $this->time($time);
        }
        if ($command !== null) {
            $this->command($command);
        }
        
        // Copy description
        $this->description = $name;
    }

    /**
     * Set name
     *
     * @param string $name
     *            Name
     *
     * @return self This object
     */
    public function name(string $name): self
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    /**
     * Set description
     *
     * @param string $description
     *            Description
     *
     * @return self This object
     */
    public function description(string $description): self
    {
        $this->attributes['description'] = $description;
        
        return $this;
    }

    /**
     * Set time
     *
     * @param mixed $time
     *            Time
     *
     * @return self This object
     */
    public function time($time): self
    {
        if (!($time instanceof TimePatternInterface)) {
            $time = new AbsoluteTime((string) $time);
        }
        
        $this->time = $time;
        
        return $this;
    }

    /**
     * Set command
     *
     * @param ActionableInterface $command
     *            Actionable command
     *
     * @return self This object
     */
    public function command(ActionableInterface $command): self
    {
        $this->command = $command;
        
        return $this;
    }

    /**
     * Set status
     *
     * @param string $status Status
     *
     * @return self This object
     */
    public function status(string $status): self
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }

    /**
     * Set autodelete
     *
     * @param bool $flag Flag
     *
     * @return self This object
     */
    public function autodelete(bool $flag): self
    {
        $this->attributes['autodelete'] = $flag;
        
        return $this;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return string Schedule Id
     */
    public function send(Client $client): string
    {
        // Set command attribute if passed
        if ($this->command) {
            $params = $this->command->getActionableParams($client);
            $params['address'] = "/api/{$client->getUsername()}" . $params['address'];
            
            $this->attributes['command'] = $params;
        }
        
        // Set time attribute if passed
        if ($this->time) {
            $this->attributes['time'] = (string) $this->time;
        }
        
        // Create schedule
        $scheduleId = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/schedules",
            TransportInterface::METHOD_POST,
            (object) $this->attributes
        );
        
        return $scheduleId;
    }
}