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
use Phue\Command\SetGroupAttributes;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\SetGroupAttributes
 */
class SetGroupAttributesTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Set up
     */
    public function setUp(): void
    {
        // Mock client
        $this->mockClient = $this->createMock('\Phue\Client', 
            array(
                'getTransport'
            ), array(
                '127.0.0.1'
            ));
        
        // Mock transport
        $this->mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock group
        $this->mockGroup = $this->createMock('\Phue\Group', null, 
            array(
                2,
                new \stdClass(),
                $this->mockClient
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
     * Test: Send command
     *
     * @covers \Phue\Command\SetGroupAttributes::__construct
     * @covers \Phue\Command\SetGroupAttributes::name
     * @covers \Phue\Command\SetGroupAttributes::lights
     * @covers \Phue\Command\SetGroupAttributes::send
     */
    public function testSend()
    {
        // Build command
        $setGroupAttributesCmd = new SetGroupAttributes($this->mockGroup);
        
        // Set expected payload
        $this->stubTransportSendRequestWithPayload(
            (object) array(
                'name' => 'Dummy!',
                'lights' => array(
                    3
                )
            ));
        
        // Change name and lights
        $setGroupAttributesCmd->name('Dummy!')
            ->
        lights(array(
            3
        ))
            ->send($this->mockClient);
    }

    /**
     * Stub transport's sendRequest with an expected payload
     *
     * @param \stdClass $payload
     *            Payload
     */
    protected function stubTransportSendRequestWithPayload(\stdClass $payload)
    {
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with(
            $this->equalTo(
                "/api/{$this->mockClient->getUsername()}/groups/{$this->mockGroup->getId()}"), 
            $this->equalTo('PUT'), $payload);
    }
}
