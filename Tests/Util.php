<?php

namespace Lsw\ApiCallerBundle\Tests;

class Util extends \PHPUnit_Framework_TestCase
{
    public function getRecording($name)
    {
        $recording = file_get_contents(__DIR__.'/_files/'.$name.'.txt');
        $recording = str_replace("\n", "\r\n", $recording);

        return $recording;
    }

    public function getCurlMock($requestPrefix = 'mocky_io_hello_world')
    {
        $curl = $this->getMock(
            'Lsw\\ApiCallerBundle\\Helper\\Curl',
            array('exec', 'getinfo')
        );

        $curl->expects($this->any())
            ->method('exec')
            ->will($this->returnValue($this->getRecording($requestPrefix.'_response_raw')));

        $curl->expects($this->any())
            ->method('getinfo')
            ->will($this->returnValueMap(array(
                array(CURLINFO_HTTP_CODE,  $this->getRecording($requestPrefix.'_curlinfo_http_code')),
                array(CURLINFO_HEADER_OUT, $this->getRecording($requestPrefix.'_curlinfo_header_out'))
            )));

        return $curl;
    }
}