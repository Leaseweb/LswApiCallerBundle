<?php

namespace Lsw\ApiCallerBundle\Helper {
    function function_exists($name)
    {
        if($name == 'curl_test') {
            return true;
        }

        return \function_exists($name);
    }

    function call_user_func_array($name, $arguments)
    {
        if($name == 'curl_test') {
            return curl_test($arguments[1]);
        }

        return \call_user_func_array($name, $arguments);
    }

    function curl_test($message)
    {
        return $message;
    }
}

namespace Lsw\ApiCallerBundle\Tests\Curl
{
    class CurlTest extends \PHPUnit_Framework_TestCase
    {
        const CLASS_NAME = '\\Lsw\\ApiCallerBundle\\Helper\\Curl';

        protected $curl, $className;

        public function setUp()
        {
            $this->className = self::CLASS_NAME;
            $this->curl = new $this->className();
        }

        public function testConstructor()
        {
            $instance = new $this->className();
            $this->assertInstanceOf($this->className, $instance);
        }

        public function testMagicCall()
        {
            $message = 'MOOO';
            $result = $this->curl->test($message);

            $this->assertEquals($message, $result);
        }

        /**
         * @expectedException Exception
         */
        public function testInvalidMagicCall()
        {
            $result = $this->curl->invalid();
        }
    }
}
