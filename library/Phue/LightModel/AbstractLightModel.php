<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\LightModel;

/**
 * Abstract light model
 */
abstract class AbstractLightModel
{
    /**
     * Model id
     *
     * @var string
     */
    protected $modelId;

    /**
     * Model name
     *
     * @var string
     */
    protected $modelName;

    /**
     * Can retain state
     *
     * @var bool
     */
    protected $canRetainState;

    /**
     * AbstractLightModel constructor.
     *
     * @param string $modelId
     * @param string $modelName
     * @param bool $canRetainState
     */
    public function __construct($modelId, $modelName, $canRetainState)
    {
        $this->modelId = $modelId;
        $this->modelName = $modelName;
        $this->canRetainState = $canRetainState;
    }

    /**
     * Get model id
     *
     * @return string Model id
     */
    public function getId()
    {
        return $this->modelId;
    }

    /**
     * Get model name
     *
     * @return string Model name
     */
    public function getName()
    {
        return $this->modelName;
    }

    /**
     * Can retain state?
     *
     * @return bool True if can, false if not
     */
    public function canRetainState()
    {
        return $this->canRetainState;
    }

    /**
     * To string.
     *
     * @return string Model name
     */
    public function __toString()
    {
        return $this->getName();
    }
}