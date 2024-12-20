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
use Phue\Command\CreateGroup;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Command\CreateGroup
 */
class CreateGroupTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Set up
     */
    public function setUp(): void
    {
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
     * Test: Set name
     *
     * @covers \Phue\Command\CreateGroup::__construct
     * @covers \Phue\Command\CreateGroup::name
     */
    public function testName()
    {
        $command = new CreateGroup('Dummy!');
        
        // Ensure property is set properly
        $this->assertAttributeEquals('Dummy!', 'name', $command);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->name('Dummy!'));
    }

    /**
     * Test: Set lights
     *
     * @covers \Phue\Command\CreateGroup::__construct
     * @covers \Phue\Command\CreateGroup::lights
     */
    public function testLights()
    {
        $command = new CreateGroup('Dummy!', array(
            1,
            2
        ));
        
        // Ensure property is set properly
        $this->assertAttributeEquals(
            array(
                1,
                2
            ), 'lights', $command);
        
        // Ensure self object is returned
        $this->assertEquals($command, $command->lights(array(
            1
        )));
    }

    /**
     * Test: Send command
     *
     * @covers \Phue\Command\CreateGroup::__construct
     * @covers \Phue\Command\CreateGroup::send
     */
    public function testSend()
    {
        $command = new CreateGroup('Dummy', array(
            2,
            3
        ));
        
        // Stub transport's sendRequest usage
        $this->mockTransport->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo("/api/{$this->mockClient->getUsername()}/groups"), 
            $this->equalTo(TransportInterface::METHOD_POST), 
            $this->equalTo(
                (object) array(
                    'name' => 'Dummy',
                    'lights' => array(
                        2,
                        3
                    )
                )))
            ->
        // ->will($this->returnValue((object)['id' => '/path/5']));
        will($this->returnValue((object) array(
            'id' => '5'
        )));
        
        // Send command and get response
        $groupId = $command->send($this->mockClient);
        
        // Ensure we have a group id
        $this->assertEquals(5, $groupId);
    }
}
