<?php

namespace Lsw\ApiCallerBundle\Tests\Logger;

use Lsw\ApiCallerBundle\Logger\ApiCallLogger;
use Lsw\ApiCallerBundle\Call\Get;
use Symfony\Bridge\Monolog\Logger;
use Monolog\Handler\NullHandler;
use Lsw\ApiCallerBundle\Tests\Util;

class ApiCallLoggerTest extends \PHPUnit_Framework_TestCase
{
    const TEST_URL = 'http://www.mocky.io/v2/';
    const TEST_COMMAND = '5185415ba171ea3a00704eed';

    protected $logger;
    protected $testRequestObject = array('some' => 'values', 'are' => 'working');

    public function setUp()
    {
        $this->logger = new ApiCallLogger(new Logger('unittest', array(new NullHandler())));

        $util = new Util();
        $curl = $util->getCurlMock('mocky_io_hello_world');
        $this->call = new Get(self::TEST_URL, self::TEST_COMMAND, $this->testRequestObject, $curl);
    }

    public function testStartCall()
    {
        $callBefore = $this->logger->currentCall;

        $this->logger->startCall($this->call);

        $start = $this->logger->start;
        $calls = $this->logger->calls;
        $currentCall = $this->logger->currentCall;

        $this->assertTrue($currentCall > $callBefore);

        $this->assertTrue(count($calls) == $currentCall);

        $this->assertInternalType('float', $start);
    }

    public function testStopCall()
    {
        $this->logger->startCall($this->call);
        $currentCallBefore = $this->logger->calls[$this->logger->currentCall];

        $this->logger->stopCall($this->call);
        $currentCallAfter = $this->logger->calls[$this->logger->currentCall];

        $this->assertTrue(count($currentCallBefore) < count($currentCallAfter));
    }
}
