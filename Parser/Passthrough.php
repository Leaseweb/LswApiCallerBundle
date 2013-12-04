<?php

namespace Lsw\ApiCallerBundle\Parser;

/**
 * This thing just returns the thing you put into this thing.
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Passthrough extends ApiParser implements ApiParserInterface
{
    /**
     * {@inheritdoc}
     *
     * @return object
     */
    public function parse($data)
    {
        return $data;
    }


}