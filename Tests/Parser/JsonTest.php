<?php

namespace Lsw\ApiCallerBundle\Tests\Parser;

class JsonTest extends ApiParserTest
{
    public function encodeSource()
    {
        $this->encodedSource = json_encode($this->source);
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testInvalidData()
    {
        $result = $this->parser->parse('{{}');
    }
}