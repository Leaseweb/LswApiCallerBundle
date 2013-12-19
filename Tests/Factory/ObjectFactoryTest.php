<?php

namespace Lsw\ApiCallerBundle\Tests\Factory;

use Lsw\ApiCallerBundle\Tests\Util;

abstract class ObjectFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $className;

    public function setUp()
    {
        $util = new Util();
        $name = $util->getClassName($this);

        $this->className = 'Lsw\\ApiCallerBundle\\Factory\\'.$name;
    }

    public function testGetCallable()
    {
        $callable = function() {};

        $className = $this->className;
        $this->assertEquals($callable, $className::get($callable));
    }

    /**
     * @expectedException Exception
     */
    public function testGetWrong()
    {
        $className = $this->className;
        $className::get('wrongwrongwrong');
    }
}