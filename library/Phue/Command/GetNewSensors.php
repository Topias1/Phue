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

/**
 * Get new sensors command
 */
class GetNewSensors implements CommandInterface
{
    /**
     * Last scan
     *
     * @var string|null
     */
    protected ?string $lastScan = null;

    /**
     * Found sensors
     *
     * @var array
     */
    protected array $sensors = [];

    /**
     * Send command
     *
     * @param Client $client
     *            Phue Client
     *
     * @return self This object
     */
    public function send(Client $client): self
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/sensors/new"
        );
        
        $this->lastScan = $response->lastscan;
        
        // Remove scan from response
        unset($response->lastscan);
        
        // Iterate through left over properties as sensors
        foreach ($response as $sensorId => $sensor) {
            $this->sensors[$sensorId] = $sensor->name;
        }
        
        return $this;
    }

    /**
     * Get sensors
     *
     * @return array List of new sensors
     */
    public function getSensors(): array
    {
        return $this->sensors;
    }

    /**
     * Is scan currently active
     *
     * @return bool True if active, false if not
     */
    public function isScanActive(): bool
    {
        return $this->lastScan === 'active';
    }
}