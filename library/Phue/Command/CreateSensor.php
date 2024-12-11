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
use Phue\Transport\TransportInterface;

/**
 * Create sensor command
 */
class CreateSensor implements CommandInterface
{
    /**
     * Sensor attributes
     *
     * @var array
     */
    protected array $attributes = [];

    /**
     * Sensor state
     *
     * @var array
     */
    protected array $state = [];

    /**
     * Config
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Constructs a command
     *
     * @param string|null $name Name
     */
    public function __construct(?string $name = null)
    {
        $this->name($name);
    }

    /**
     * Set name
     *
     * @param string $name Name
     *
     * @return self This object
     */
    public function name(string $name): self
    {
        $this->attributes['name'] = $name;
        
        return $this;
    }

    /**
     * Set model Id
     *
     * @param string $modelId Model Id
     *
     * @return self This object
     */
    public function modelId(string $modelId): self
    {
        $this->attributes['modelid'] = $modelId;
        
        return $this;
    }

    /**
     * Set software version
     *
     * @param string $softwareVersion Software version
     *
     * @return self This object
     */
    public function softwareVersion(string $softwareVersion): self
    {
        $this->attributes['swversion'] = $softwareVersion;
        
        return $this;
    }

    /**
     * Set type
     *
     * @param string $type Type of sensor
     *
     * @return self This object
     */
    public function type(string $type): self
    {
        $this->attributes['type'] = $type;
        
        return $this;
    }

    /**
     * Set unique Id
     *
     * @param string $uniqueId Unique Id
     *
     * @return self This object
     */
    public function uniqueId(string $uniqueId): self
    {
        $this->attributes['uniqueid'] = $uniqueId;
        
        return $this;
    }

    /**
     * Set manufacturer name
     *
     * @param string $manufacturerName Manufacturer name
     *
     * @return self This object
     */
    public function manufacturerName(string $manufacturerName): self
    {
        $this->attributes['manufacturername'] = $manufacturerName;
        
        return $this;
    }

    /**
     * State attribute
     *
     * @param string $key Key
     * @param mixed $value Value
     *
     * @return self This object
     */
    public function stateAttribute(string $key, $value): self
    {
        $this->state[$key] = $value;
        
        return $this;
    }

    /**
     * Config attribute
     *
     * @param string $key Key
     * @param mixed $value Value
     *
     * @return self This object
     */
    public function configAttribute(string $key, $value): self
    {
        $this->config[$key] = $value;
        
        return $this;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return int Sensor Id
     */
    public function send(Client $client): int
    {
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors",
            TransportInterface::METHOD_POST,
            (object) array_merge(
                $this->attributes,
                [
                    'state' => $this->state,
                    'config' => $this->config
                ]
            )
        );
        
        return (int) $response->id;
    }
}