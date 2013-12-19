<?php

namespace Lsw\ApiCallerBundle\Tests\Call;

use Lsw\ApiCallerBundle\Tests\Util;

abstract class CurlCallTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_PREFIX = 'Lsw\\ApiCallerBundle\\Call\\';
    const TEST_URL = 'http://www.mocky.io/v2/';
    const TEST_COMMAND = '5185415ba171ea3a00704eed';

    protected $testRequestObject = array('some' => 'values', 'are' => 'working');

    protected $call;
    protected $className;
    protected $util;

    protected function executeCall($parser = null)
    {
        $result = $this->call->execute(array(), $parser);
        return $result;
    }

    protected function setUpClassName()
    {
        $util = new Util();
        $name = $util->getClassName($this);

        $this->className = static::CLASS_PREFIX.$name;
    }

    public function setUp()
    {
        $this->setUpClassName();

        $this->util = new Util();
        $curl = $this->util->getCurlMock();

        $this->call = new $this->className(self::TEST_URL, self::TEST_COMMAND, $this->testRequestObject, $curl);

        $this->executeCall();
    }

    public function testConstructor()
    {
        $this->setUpClassName();
        $instance = new $this->className(self::TEST_URL, self::TEST_COMMAND, $this->testRequestObject);
        $this->assertInstanceOf($this->className, $instance);
    }

    public function testGetUrl()
    {
        $url = $this->call->getUrl();

        $this->assertEquals(self::TEST_URL, $url);
    }

    public function testGetName()
    {
        $this->assertEquals($this->call->getName(), $this->className);
    }

    public function testGetRequestData()
    {
        $this->assertEquals($this->call->getRequestData(), http_build_query($this->testRequestObject));
    }

    public function testGetRequestObject()
    {
        $this->assertEquals($this->call->getRequestObject(), $this->testRequestObject);
    }

    public function testGetRequestObjectRepresentation()
    {
        $this->assertInternalType('string', $this->call->getRequestObjectRepresentation());
    }

    public function testGetRequestHeaders()
    {
        $this->assertEquals($this->call->getRequestHeaders(), $this->util->getRecording('mocky_io_hello_world_curlinfo_header_out'));
    }

    public function testGetResponseData()
    {
        $this->assertEquals($this->call->getResponseData(), $this->util->getRecording('mocky_io_hello_world_response_data'));
    }

    public function testGetResponseObject()
    {
        $this->assertEquals($this->call->getResponseObject(), $this->util->getRecording('mocky_io_hello_world_response_data'));

        $parser = new \Lsw\ApiCallerBundle\Parser\Json();
        $this->executeCall($parser);
        $this->assertEquals($this->call->getResponseObject(), $parser($this->util->getRecording('mocky_io_hello_world_response_data')));
    }

    public function testGetResponseHeaders()
    {
        $this->assertEquals($this->call->getResponseHeaders(), $this->util->getRecording('mocky_io_hello_world_response_headers'));
    }

    public function testGetResponseObjectRepresentation()
    {
        $this->assertInternalType('string', $this->call->getResponseObjectRepresentation());
    }

    public function testGetStatusCode()
    {
        $this->assertEquals($this->call->getStatusCode(), $this->util->getRecording('mocky_io_hello_world_curlinfo_http_code'));
    }

    public function testGetStatus()
    {
        $this->assertInternalType('string', $this->call->getStatus());
    }

    public function testExecuteSuccess()
    {
        $result = $this->executeCall();
        $this->assertEquals($result, $this->util->getRecording('mocky_io_hello_world_response_data'));
    }

}
