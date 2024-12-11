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
use Phue\Condition;
use Phue\Transport\TransportInterface;

/**
 * Create rule command
 */
class CreateRule implements CommandInterface
{
    /**
     * Name
     *
     * @var string
     */
    protected string $name;

    /**
     * Conditions
     *
     * @var Condition[]
     */
    protected array $conditions = [];

    /**
     * Actions
     *
     * @var ActionableInterface[]
     */
    protected array $actions = [];

    /**
     * Constructs a command
     *
     * @param string $name Name
     */
    public function __construct(string $name = '')
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
        $this->name = $name;
        return $this;
    }

    /**
     * Add condition
     *
     * @param Condition $condition Condition
     *
     * @return self This object
     */
    public function addCondition(Condition $condition): self
    {
        $this->conditions[] = $condition;
        return $this;
    }

    /**
     * Add actionable command
     *
     * @param ActionableInterface $action Actionable command
     *
     * @return self This object
     */
    public function addAction(ActionableInterface $action): self
    {
        $this->actions[] = $action;
        return $this;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return string Rule Id
     */
    public function send(Client $client): string
    {
        $response = $client->getTransport()->sendRequest(
            "/api/{$client->getUsername()}/rules",
            TransportInterface::METHOD_POST,
            (object) [
                'name' => $this->name,
                'conditions' => array_map(
                    fn($condition) => $condition->export(),
                    $this->conditions
                ),
                'actions' => array_map(
                    fn($action) => $action->getActionableParams($client),
                    $this->actions
                )
            ]
        );
        
        return (string) $response->id;
    }
}