<?php

namespace Lsw\ApiCallerBundle\Tests\Factory;

use Lsw\ApiCallerBundle\Factory\ApiCallerFactory;

class ApiCallerFactoryTest extends \PHPUnit_Framework_TestCase
{
    protected $factory;

    protected $options = array(
        'phpunit'   => array(
            'endpoint'  => 'http://example.com/',
            'format'    => 'passthrough',
            'engine'    => array()
        )
    );

    public function setUp()
    {
        $this->factory = new ApiCallerFactory($this->options);
    }

    public function testApi()
    {
        $api = $this->factory->api('phpunit');
        $this->assertInternalType('object', $api);

        // api was already initiated check
        $api = $this->factory->api('phpunit');
        $this->assertInternalType('object', $api);
    }

    /**
     * @expectedException Exception
     */
    public function testApiWrong()
    {
        $api = $this->factory->api('wrongwrong');
    }
}