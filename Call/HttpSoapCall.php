<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * Soap API call using SoapClient
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpSoapCall extends SoapCall implements ApiCallInterface
{
    /**
    * {@inheritdoc}
    */
    public function generateRequestData()
    {
        $this->requestData = json_encode($this->requestObject);
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponseData()
    {
        $this->responseObject = json_decode($this->responseData);
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest()
    {
        $this->engine->__setLocation($this->url);
        $this->status = 500;
        $this->responseData = json_encode($this->engine->__call($this->call, array($this->requestObject)));
        $this->status = 200;
    }

}
