<?php

namespace Lsw\ApiCallerBundle\Tests\DataCollector;

use Lsw\ApiCallerBundle\DataCollector\ApiCallDataCollector;
use Lsw\ApiCallerBundle\Logger\ApiCallLogger;
use Symfony\Bridge\Monolog\Logger;
use Monolog\Handler\NullHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lsw\ApiCallerBundle\Call\Get;
use Lsw\ApiCallerBundle\Tests\Util;


class ApiCallDataCollectorTest extends \PHPUnit_Framework_TestCase
{
    const TEST_URL = 'http://www.mocky.io/v2/';
    const TEST_COMMAND = '5185415ba171ea3a00704eed';

    const TEST_WRONG_COMMAND = '52b2d62a4a61591802f999b6';

    protected $testRequestObject = array('some' => 'values', 'are' => 'working');

    protected $logger;
    protected $util;
    protected $collector;

    protected function executeCall($type = 'correct')
    {
        if($type == 'error') {
            $name = 'mocky_io_please_behave';
            $command = self::TEST_WRONG_COMMAND;
        } else {
            $name = 'mocky_io_hello_world';
            $command = self::TEST_COMMAND;
        }

        $curl = $this->util->getCurlMock($name);
        $call = new Get(self::TEST_URL, $command, $this->testRequestObject, $curl);

        $this->logger->startCall($call);
        $call->execute();
        $this->logger->stopCall($call);
    }

    protected function callAndCollect()
    {
        $this->executeCall();
        $this->executeCall('error');
        $this->executeCall();

        $this->collector->collect(new Request(), new Response());
    }

    public function setUp()
    {
        $this->util = new Util();
        $this->logger = new ApiCallLogger(new Logger('unittest', array(new NullHandler())));
        $this->collector = new ApiCallDataCollector($this->logger);
    }

    public function testGetReturnedErrorCount()
    {
        $this->callAndCollect();
        $this->assertEquals(1, $this->collector->getReturnedErrorCount());
    }

    public function testGetCallCount()
    {
        $this->callAndCollect();
        $this->assertEquals(3, $this->collector->getCallCount());
    }

    public function testGetCalls()
    {
        $this->callAndCollect();
        $this->assertEquals(3, count($this->collector->getCalls()));
    }

    public function testGetTime()
    {
        $this->callAndCollect();
        $this->assertInternalType('float', $this->collector->getTime());
    }

    public function testGetName()
    {
        $this->assertInternalType('string', $this->collector->getName());
    }
}
