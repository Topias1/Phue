<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test\Command;

use Phue\Client;
use Phue\Command\GetBridge;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\GetBridge
 */
class GetBridgeTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Set up
     */
    public function setUp(): void
    {
        $this->getBridge = new GetBridge();
        
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client', 
            array(
                'getUsername',
                'getTransport'
            ), array(
                '127.0.0.1'
            ));
        
        // Mock transport
        $this->mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Stub client's getUsername method
        $this->mockClient->expects($this->any())
            ->method('getUsername')
            ->will($this->returnValue('abcdefabcdef01234567890123456789'));
        
        // Stub client's getTransport method
        $this->mockClient->expects($this->any())
            ->method('getTransport')
            ->will($this->returnValue($this->mockTransport));
    }

    /**
     * Test: Get Bridge
     *
     * @covers \Phue\Command\GetBridge::send
     */
    public function testGetBridge()
    {
        // Mock transport results
        $mockTransportResults = new \stdClass();
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/config"))
            ->will($this->returnValue($mockTransportResults));
        
        // Send command and get response
        $response = $this->getBridge->send($this->mockClient);
        
        // Ensure we have a bridge object
        $this->assertInstanceOf('\Phue\Bridge', $response);
    }
}
