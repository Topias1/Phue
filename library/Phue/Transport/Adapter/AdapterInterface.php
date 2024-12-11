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
 * Adapter Interface
 */
interface AdapterInterface
{
    /**
     * Opens the connection
     *
     * @return void
     */
    public function open(): void;

    /**
     * Sends request
     *
     * @param string $address Request path
     * @param string $method  Request method
     * @param string|null $body Body data
     *
     * @return string Result
     */
    public function send(string $address, string $method, ?string $body = null): string;

    /**
     * Get HTTP status code from response
     *
     * @return int Status code
     */
    public function getHttpStatusCode(): int;

    /**
     * Get content type from response
     *
     * @return string Content type
     */
    public function getContentType(): string;

    /**
     * Closes the connection
     *
     * @return void
     */
    public function close(): void;
}