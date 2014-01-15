<?php

namespace Lsw\ApiCallerBundle\Parser;

/**
 * Result parser interface
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
interface ApiParserInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function __invoke($data);

    /**
     * Parses passed data
     *
     * @param string $data
     *
     * @return mixed
     */
    public function parse($data);
}