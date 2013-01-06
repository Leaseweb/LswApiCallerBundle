<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpGetJson extends ApiCall implements ApiCallInterface
{
    /**
    * {@inheritdoc}
    */
    public function generateRequestData()
    {
        $this->requestData = http_build_query($this->requestObject);
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
    public function makeRequest($curlHandle, $options)
    {
        curl_setopt($curlHandle, CURLOPT_URL, $this->url.'?'.$this->requestData);
        curl_setopt_array($curlHandle, $options);
        $this->responseData = curl_exec($curlHandle);
    }

}
