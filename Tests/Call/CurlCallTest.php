<?php

namespace Lsw\ApiCallerBundle\Tests\Call;

abstract class CurlCallTest extends \PHPUnit_Framework_TestCase
{
    const CLASS_PREFIX = 'Lsw\\ApiCallerBundle\\Call\\';
    const TEST_URL = 'http://www.mocky.io/v2/';
    const TEST_COMMAND = '5185415ba171ea3a00704eed';

    protected $testRequestObject = array('some' => 'values', 'are' => 'working');

    protected $call;
    protected $className;

    protected function getRecording($name)
    {
        $recording = file_get_contents(__DIR__.'/_files/'.$name.'.txt');
        $recording = str_replace("\n", "\r\n", $recording);

        return $recording;
    }

    protected function executeCall($parser = null)
    {
        $result = $this->call->execute(array(), $parser);
        return $result;
    }

    protected function setUpClassName()
    {
        $name = get_class($this);
        $name = substr($name, strrpos($name, '\\')+1);
        $name = substr($name, 0, strrpos($name, 'Test'));

        $this->className = static::CLASS_PREFIX.$name;
    }

    public function setUp()
    {
        $this->setUpClassName();

        $curl = $this->getMock(
            'Lsw\\ApiCallerBundle\\Helper\\Curl',
            array('exec', 'getinfo')
        );

        $curl->expects($this->any())
            ->method('exec')
            ->will($this->returnValue($this->getRecording('mocky_io_hello_world_response_raw')));

        $curl->expects($this->any())
            ->method('getinfo')
            ->will($this->returnValueMap(array(
                array(CURLINFO_HTTP_CODE,  $this->getRecording('mocky_io_hello_world_curlinfo_http_code')),
                array(CURLINFO_HEADER_OUT, $this->getRecording('mocky_io_hello_world_curlinfo_header_out'))
            )));


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
        $this->assertEquals($this->call->getRequestHeaders(), $this->getRecording('mocky_io_hello_world_curlinfo_header_out'));
    }

    public function testGetResponseData()
    {
        $this->assertEquals($this->call->getResponseData(), $this->getRecording('mocky_io_hello_world_response_data'));
    }

    public function testGetResponseObject()
    {
        $this->assertEquals($this->call->getResponseObject(), $this->getRecording('mocky_io_hello_world_response_data'));

        $parser = new \Lsw\ApiCallerBundle\Parser\Json();
        $this->executeCall($parser);
        $this->assertEquals($this->call->getResponseObject(), $parser($this->getRecording('mocky_io_hello_world_response_data')));
    }

    public function testGetResponseHeaders()
    {
        $this->assertEquals($this->call->getResponseHeaders(), $this->getRecording('mocky_io_hello_world_response_headers'));
    }

    public function testGetResponseObjectRepresentation()
    {
        $this->assertInternalType('string', $this->call->getResponseObjectRepresentation());
    }

    public function testGetStatusCode()
    {
        $this->assertEquals($this->call->getStatusCode(), $this->getRecording('mocky_io_hello_world_curlinfo_http_code'));
    }

    public function testGetStatus()
    {
        $this->assertInternalType('string', $this->call->getStatus());
    }

    public function testExecuteSuccess()
    {
        $result = $this->executeCall();
        $this->assertEquals($result, '{"hello": "world"}');
    }

}
