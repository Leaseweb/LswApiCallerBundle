<?php

namespace Lsw\ApiCallerBundle\Factory;

/**
 * API objects factory
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */

class ObjectFactory
{
    /**
     * Builds object of the current namespace.
     *
     * @param string    $name       Name of the object
     * @param array     $arguments  Arguments to pass to the constructor
     *
     * @throws \Exception If it's not possible to initiate class
     *
     * @return object
     */
    static public function get($name, $arguments = array())
    {
        if(is_callable($name)) {
            return $name;
        }

        $name = ucfirst($name);
        $namespace = static::TARGET;

        $class = $namespace.'\\'.$name;

        if (class_exists($class)) {
            $reflection  = new \ReflectionClass($class);
            $instance = $reflection->newInstanceArgs($arguments);

            return $instance;
        }

        throw new \Exception('Missing class '.$namespace.'\\'. $name);
    }
}