<?php

namespace Lsw\ApiCallerBundle\Parser;

/**
 * Result parser parent class
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class ApiParser
{
    public function __invoke($data)
    {
        return $this->parse($data);
    }

}