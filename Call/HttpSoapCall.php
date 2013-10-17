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
    public function makeRequest($soap)
    {
        $soap->__setLocation($this->url);
        $this->status = 500;
        $this->responseData = json_encode($soap->__call($this->call, array($this->requestObject)));
        $this->status = 200;
    }

}
