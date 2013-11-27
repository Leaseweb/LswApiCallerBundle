<?php

namespace Lsw\ApiCallerBundle\Factory;

/**
 * API objects factory
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */

class ApiObjectFactory
{
    static public function get($parser, $arguments)
    {
        $namespace = (new \ReflectionClass(get_called_class()))->getNamespaceName();

        if (is_object($parser)) {
            return $parser;
        } else {
            $class = $namespace.'\\'.$parser;
            if (is_string($parser) && class_exists($class)) {
                $reflect  = new \ReflectionClass($class);
                $instance = $reflect->newInstanceArgs($arguments);

                return $instance;
            }
        }

        throw new \Exception('Missing class:'.$parser);
    }
}