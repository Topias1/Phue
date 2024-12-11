<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Transport\Adapter;

/**
 * cURL Http adapter
 */
class Curl implements AdapterInterface
{
    /**
     * cURL resource
     *
     * @var resource|null
     */
    protected ?resource $curl = null;

    /**
     * Constructs a cURL adapter
     *
     * @throws \BadFunctionCallException if the cURL extension is not loaded
     */
    public function __construct()
    {
        // Throw exception if cURL extension is not loaded
        if (!extension_loaded('curl')) {
            throw new \BadFunctionCallException('The cURL extension is required.');
        }
    }

    /**
     * Opens the connection
     *
     * @return void
     */
    public function open(): void
    {
        $this->curl = curl_init();
    }

    /**
     * Sends request
     *
     * @param string $address Request path
     * @param string $method  Request method
     * @param string|null $body Body data
     *
     * @return string The response result
     */
    public function send(string $address, string $method, ?string $body = null): string
    {
        // Set connection options
        curl_setopt($this->curl, CURLOPT_URL, $address);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($this->curl, CURLOPT_HEADER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

        if ($body !== null && strlen($body)) {
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($this->curl);

        if ($response === false) {
            throw new \RuntimeException('cURL error: ' . curl_error($this->curl));
        }

        return $response;
    }

    /**
     * Get response HTTP status code
     *
     * @return int Response HTTP status code
     */
    public function getHttpStatusCode(): int
    {
        return (int)curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
    }

    /**
     * Get response content type
     *
     * @return string Response content type
     */
    public function getContentType(): string
    {
        return (string)curl_getinfo($this->curl, CURLINFO_CONTENT_TYPE);
    }

    /**
     * Closes the cURL connection
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
            $this->curl = null;
        }
    }
}