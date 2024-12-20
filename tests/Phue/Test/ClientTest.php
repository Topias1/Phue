<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */
namespace Phue\Test;

use Phue\Client;
use Phue\Command\CommandInterface;
use Phue\Transport\TransportInterface;

/**
 * Tests for Phue\Client
 */
class ClientTest extends \PHPUnit\Framework\TestCase
{

    /**
     * Set up
     *
     * @covers \Phue\Client::__construct
     */
    public function setUp(): void
    {
        $this->client = new Client('127.0.0.1');
    }

    /**
     * Test: Get host
     *
     * @covers \Phue\Client::getHost
     * @covers \Phue\Client::setHost
     */
    public function testGetHost()
    {
        $this->client->setHost('127.0.0.2');
        
        $this->assertEquals('127.0.0.2', $this->client->getHost());
    }

    /**
     * Test: Setting non-hashed username
     *
     * @covers \Phue\Client::getUsername
     * @covers \Phue\Client::setUsername
     */
    public function testGetSetUsername()
    {
        $this->client->setUsername('dummy');
        
        $this->assertEquals('dummy', $this->client->getUsername());
    }

    /**
     * Test: Get bridge
     *
     * @covers \Phue\Client::getBridge
     */
    public function testGetBridge()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue(new \stdClass()));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Ensure return type is Bridge
        $this->assertInstanceOf('\Phue\Bridge', $this->client->getBridge());
    }

    /**
     * Test: Get users
     *
     * @covers \Phue\Client::getUsers
     */
    public function testGetUsers()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        $mockResults = (object) array(
            'whitelist' => array(
                'someusername' => new \stdClass(),
                'anotherusername' => new \stdClass(),
                'thirdusername' => new \stdClass()
            )
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get users
        $users = $this->client->getUsers();
        
        // Ensure at least three users
        $this->assertEquals(3, count($users));
        
        // Ensure return type is an array of users
        $this->assertContainsOnlyInstancesOf('\Phue\User', $users);
    }

    /**
     * Test: Get lights
     *
     * @covers \Phue\Client::getLights
     */
    public function testGetLights()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        // $mockResults = (object) [
        // '1' => new \stdClass,
        // '2' => new \stdClass,
        // ];
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get lights
        $lights = $this->client->getLights();
        
        // Ensure two lights
        $this->assertEquals(2, count($lights));
        
        // Ensure return type is an array of lights
        $this->assertContainsOnlyInstancesOf('\Phue\Light', $lights);
    }

    /**
     * Test: Get groups
     *
     * @covers \Phue\Client::getGroups
     */
    public function testGetGroups()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        // $mockResults = (object) [
        // '1' => new \stdClass,
        // '2' => new \stdClass,
        // ];
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get groups
        $groups = $this->client->getGroups();
        
        // Ensure two groups
        $this->assertEquals(2, count($groups));
        
        // Ensure return type is an array of groups
        $this->assertContainsOnlyInstancesOf('\Phue\Group', $groups);
    }

    /**
     * Test: Get schedules
     *
     * @covers \Phue\Client::getSchedules
     */
    public function testGetSchedules()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass(),
            '3' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get schedules
        $schedules = $this->client->getSchedules();
        
        // Ensure three schedules
        $this->assertEquals(3, count($schedules));
        
        // Ensure return type is an array of schedules
        $this->assertContainsOnlyInstancesOf('\Phue\Schedule', $schedules);
    }

    /**
     * Test: Get scenes
     *
     * @covers \Phue\Client::getScenes
     */
    public function testGetScenes()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        // $mockResults = (object) [
        // '1' => new \stdClass,
        // '2' => new \stdClass,
        // '3' => new \stdClass,
        // ];
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass(),
            '3' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get scenes
        $scenes = $this->client->getScenes();
        
        // Ensure three scenes
        $this->assertEquals(3, count($scenes));
        
        // Ensure return type is an array of scenes
        $this->assertContainsOnlyInstancesOf('\Phue\Scene', $scenes);
    }

    /**
     * Test: Get sensors
     *
     * @covers \Phue\Client::getSensors
     */
    public function testGetSensors()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get sensors
        $sensors = $this->client->getSensors();
        
        // Ensure two sensors
        $this->assertEquals(2, count($sensors));
        
        // Ensure return type is an array of sensors
        $this->assertContainsOnlyInstancesOf('\Phue\Sensor', $sensors);
    }

    /**
     * Test: Get rules
     *
     * @covers \Phue\Client::getRules
     */
    public function testGetRules()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest'
            ));
        
        // Mock results for sendRequest
        $mockResults = (object) array(
            '1' => new \stdClass(),
            '2' => new \stdClass()
        );
        
        // Stub transports sendRequest method
        $mockTransport->expects($this->once())
            ->method('sendRequest')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get rules
        $rules = $this->client->getRules();
        
        // Ensure two rules
        $this->assertEquals(2, count($rules));
        
        // Ensure return type is an array of rules
        $this->assertContainsOnlyInstancesOf('\Phue\Rule', $rules);
    }

    /**
     * Test: Get timezones
     *
     * @covers \Phue\Client::getTimezones
     */
    public function testGetTimezones()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface', 
            array(
                'sendRequest',
                'sendRequestBypassBodyValidation'
            ));
        
        // Mock results for sendRequestBypassBodyValidation
        $mockResults = array();
        
        // Stub transports sendRequestBypassBodyValidation method
        $mockTransport->expects($this->once())
            ->method('sendRequestBypassBodyValidation')
            ->will($this->returnValue($mockResults));
        
        // Set transport
        $this->client->setTransport($mockTransport);
        
        // Get timezones
        $timezones = $this->client->getTimezones();
        
        // Ensure we get an array
        $this->assertSame($mockResults, $timezones);
    }

    /**
     * Test: Not passing in Transport dependency will yield default
     *
     * @covers \Phue\Client::getTransport
     * @covers \Phue\Client::setTransport
     */
    public function testInstantiateDefaultTransport()
    {
        $this->assertInstanceOf('\Phue\Transport\Http', 
            $this->client->getTransport());
    }

    /**
     * Test: Passing custom Transport to client
     *
     * @covers \Phue\Client::getTransport
     * @covers \Phue\Client::setTransport
     */
    public function testPassingTransportDependency()
    {
        // Mock transport
        $mockTransport = $this->createMock('\Phue\Transport\TransportInterface');
        
        $this->client->setTransport($mockTransport);
        
        $this->assertEquals($mockTransport, $this->client->getTransport());
    }

    /**
     * Test: Sending a command
     *
     * @covers \Phue\Client::sendCommand
     */
    public function testSendCommand()
    {
        // Mock command
        $mockCommand = $this->createMock('Phue\Command\CommandInterface', 
            array(
                'send'
            ));
        
        // Stub command's send method
        $mockCommand->expects($this->once())
            ->method('send')
            ->with($this->equalTo($this->client))
            ->will($this->returnValue('sample response'));
        
        $this->assertEquals('sample response', 
            $this->client->sendCommand($mockCommand));
    }
}
