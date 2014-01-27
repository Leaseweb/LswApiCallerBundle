<?php

namespace Lsw\ApiCallerBundle\Tests\Call;

use Lsw\ApiCallerBundle\Logger\ApiCallLogger;
use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\Get;
use Lsw\ApiCallerBundle\Tests\Util;

class LoggingApiCallerTest extends \PHPUnit_Framework_TestCase
{
    const TEST_COMMAND = '5185415ba171ea3a00704eed';

    protected $options = array(
        'endpoint'  => 'http://www.mocky.io/v2/',
        'format'    => 'Json',
        'engine'    => array()
    );
    protected $testRequestObject = array('some' => 'values', 'are' => 'working');

    protected $caller;
    protected $call;

    public function setUp()
    {
        $this->util = new Util();
        $this->caller = new LoggingApiCaller($this->options, new ApiCallLogger());

        $curl = $this->util->getCurlMock();
        $this->call = new Get($this->options['endpoint'], self::TEST_COMMAND, $this->testRequestObject, $curl);
    }

    // Warning: this test does not use recorded data
    public function testMagicCall()
    {
        $this->caller->oneTimeParser('passthrough');
        $result = $this->caller->getCall(self::TEST_COMMAND);

        $this->assertEquals($result, $this->util->getRecording('mocky_io_hello_world_response_data'));
    }

    public function testWrongMagicCall()
    {
        $this->assertNull($this->caller->wrongCallName());
    }

    /**
     * @expectedException Exception
     */
    public function testMagicCallWithWrongParser()
    {
        $this->caller->wrongParserCall();
    }

    public function testGetLastStatus()
    {
        $this->assertNull($this->caller->getLastStatus());

        $this->callAndParse();
        $this->assertInternalType('string', $this->caller->getLastStatus());
    }

    protected function callAndParse($parser = null)
    {
        $parser = $parser ?: new \Lsw\ApiCallerBundle\Parser\Json();

        $result = $this->caller->call($this->call);

        $this->assertEquals($result, $parser($this->util->getRecording('mocky_io_hello_world_response_data')));
    }

    public function testCall()
    {
        $this->callAndParse();
    }

    public function testOnetimeParser()
    {
        $this->caller->oneTimeParser('passthrough');

        $parser = new \Lsw\ApiCallerBundle\Parser\Passthrough();
        $this->callAndParse($parser);

        $this->callAndParse();
    }

    public function testResetParser()
    {
        $this->caller->oneTimeParser('passthrough');
        $this->caller->resetParser();

        $this->callAndParse();
    }

    public function testOnetimeEngineOptionAndResetEngineOptions()
    {
        $testOption = '_unittest';

        $call = $this->getMock(
            'Lsw\\ApiCallerBundle\\Call\\Get',
            array('execute'),
            array(
                $this->options['endpoint'],
                self::TEST_COMMAND,
                $this->testRequestObject
            )
        );

        $call->expects($this->any())
            ->method('execute')
            ->will($this->returnCallback(function() use ($testOption) {
                $options = func_get_arg(0);
                return (isset($options[$testOption]));
            }));

        $this->caller->onetimeEngineOption($testOption, true);
        $result = $this->caller->call($call);
        $this->assertTrue($result);

        $result = $this->caller->call($call);
        $this->assertFalse($result);

        $this->caller->onetimeEngineOption($testOption, true);
        $this->caller->resetEngineOptions();
        $result = $this->caller->call($call);
        $this->assertFalse($result);
    }

}