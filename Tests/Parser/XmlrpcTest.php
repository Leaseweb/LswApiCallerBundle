<?php

namespace Lsw\ApiCallerBundle\Tests\Parser;

class XmlrpcTest extends ApiParserTest
{
    public function encodeSource()
    {
        $this->encodedSource = xmlrpc_encode($this->source);
    }

    /**
     */
    public function testInvalidData()
    {
        $result = $this->parser->parse('Wrong!');

        $this->assertNull($result);
    }
}