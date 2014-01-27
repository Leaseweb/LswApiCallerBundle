<?php

namespace Lsw\ApiCallerBundle\Tests\Factory;

use Lsw\ApiCallerBundle\Call\ApiCallInterface;

class CallFactoryTest extends ObjectFactoryTest
{
    public function testGetClass()
    {
        $className = $this->className;
        $result = $className::get('post', array('test'));

        $this->assertTrue($result instanceof ApiCallInterface);
    }
}