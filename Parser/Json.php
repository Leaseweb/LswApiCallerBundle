<?php

namespace Lsw\ApiCallerBundle\Parser;

/**
 * Result parser for json
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Json implements ApiParserInterface
{
    /**
     * {@inheritdoc}
     *
     * @return object
     */
    public function parse($data)
    {
        $parsed = json_decode($data);

        return $parsed;
    }


}