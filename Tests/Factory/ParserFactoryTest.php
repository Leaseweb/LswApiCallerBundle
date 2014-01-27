<?php

namespace Lsw\ApiCallerBundle\Tests\Factory;

use Lsw\ApiCallerBundle\Parser\ApiParserInterface;

class ParserFactoryTest extends ObjectFactoryTest
{
    public function testGetClass()
    {
        $className = $this->className;
        $result = $className::get('json');

        $this->assertTrue($result instanceof ApiParserInterface);
    }
}