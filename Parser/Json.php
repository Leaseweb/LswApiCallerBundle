<?php

namespace Lsw\ApiCallerBundle\Parser;

/**
 * Result parser for json
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Json extends ApiParser implements ApiParserInterface
{
    /**
     * {@inheritdoc}
     *
     * @return object
     */
    public function parse($data)
    {
        $parsed = json_decode($data);

        //TODO: change to json_last_error_message when 5.5 will be adopted
        if($error = json_last_error()) {
            // PHP 5.5
            $errorText = (function_exists('json_last_error_msg')) ? json_last_error_msg() : $error;

            throw new \UnexpectedValueException('JSON Error: '.$errorText, $error);
        }

        return $parsed;
    }


}