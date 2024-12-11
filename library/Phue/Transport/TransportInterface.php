<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport;

/**
 * Transport Interface
 */
interface TransportInterface
{
    /**
     * Get method
     */
    public const METHOD_GET = 'GET';

    /**
     * Post method
     */
    public const METHOD_POST = 'POST';

    /**
     * Put method
     */
    public const METHOD_PUT = 'PUT';

    /**
     * Delete method
     */
    public const METHOD_DELETE = 'DELETE';

    /**
     * Send request
     *
     * @param string $address API path
     * @param string $method Request method
     * @param \stdClass|null $body Body data (optional)
     *
     * @return mixed Command result
     */
    public function sendRequest(string $address, string $method = self::METHOD_GET, ?\stdClass $body = null);

    /**
     * Send request, bypass body validation
     *
     * @param string $address API path
     * @param string $method Request method
     * @param \stdClass|null $body Body data (optional)
     *
     * @return mixed Command result
     */
    public function sendRequestBypassBodyValidation(string $address, string $method = self::METHOD_GET, ?\stdClass $body = null);
}