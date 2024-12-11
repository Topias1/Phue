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
 * Streaming Http adapter
 */
class Streaming implements AdapterInterface
{
    /**
     * Stream context
     *
     * @var resource|null
     */
    protected ?resource $streamContext = null;

    /**
     * File stream
     *
     * @var resource|null
     */
    protected ?resource $fileStream = null;

    /**
     * Opens the connection
     *
     * @return void
     */
    public function open(): void
    {
        // Deliberately do nothing
    }

    /**
     * Sends request
     *
     * @param string $address Request path
     * @param string $method Request method
     * @param string|null $body Body data
     *
     * @return string|false Result or false on failure
     */
    public function send(string $address, string $method, ?string $body = null)
    {
        // Init stream options
        $streamOptions = [
            'ignore_errors' => true,
            'method' => $method,
        ];

        // Set body if there is one
        if ($body !== null && strlen($body)) {
            $streamOptions['content'] = $body;
        }

        $this->streamContext = stream_context_create([
            'http' => $streamOptions
        ]);

        // Make request
        $this->fileStream = @fopen($address, 'r', false, $this->streamContext);

        return $this->fileStream ? stream_get_contents($this->fileStream) : false;
    }

    /**
     * Get response http status code
     *
     * @return int|string|false Response HTTP code or false on failure
     */
    public function getHttpStatusCode()
    {
        $headers = $this->getHeaders();
        preg_match('#^HTTP/1\.1 (\d+)#mi', $headers, $matches);

        return isset($matches[1]) ? (int)$matches[1] : false;
    }

    /**
     * Get response content type
     *
     * @return string|false Response content type or false on failure
     */
    public function getContentType()
    {
        $headers = $this->getHeaders();
        preg_match('#^Content-type: ([^;]+?)$#mi', $headers, $matches);

        return isset($matches[1]) ? $matches[1] : false;
    }

    /**
     * Get headers
     *
     * @return string|null Headers or null if file stream is invalid
     */
    public function getHeaders(): ?string
    {
        // Don't continue if file stream isn't valid
        if ($this->fileStream === null) {
            return null;
        }

        $metaData = stream_get_meta_data($this->fileStream);
        return implode("\r\n", $metaData['wrapper_data']);
    }

    /**
     * Closes the streaming connection
     *
     * @return void
     */
    public function close(): void
    {
        if (is_resource($this->fileStream)) {
            fclose($this->fileStream);
        }

        $this->streamContext = null;
    }
}