<?php

namespace Lsw\ApiCallerBundle\Tests\Parser;

use Lsw\ApiCallerBundle\Tests\Util;

abstract class ApiParserTest extends \PHPUnit_Framework_TestCase
{
    protected $parser;
    protected $encoudedSource;
    protected $source = 'Test string';

    public function setUp()
    {
        $util = new Util();
        $name = $util->getClassName($this);

        $class = 'Lsw\\ApiCallerBundle\\Parser\\'.$name;
        $this->parser = new $class();

        $this->encodeSource();
    }

    public function encodeSource()
    {
        $this->encodedSource = $this->source;
    }

    public function testAsObject()
    {
        $result = $this->parser->parse($this->encodedSource);
        $this->assertEquals($this->source, $result);
    }

    public function testAsCallable()
    {
        // $this->parser() will not work. PHP limitation.
        $parser = $this->parser;

        $result = $parser($this->encodedSource);
        $this->assertEquals($this->source, $result);
    }
}