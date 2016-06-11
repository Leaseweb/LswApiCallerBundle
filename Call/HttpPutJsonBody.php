<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data sent as JSON body
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpPutJsonBody extends CurlCall implements ApiCallInterface
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
        $this->responseObject = json_decode($this->responseData,$this->asAssociativeArray);
    }
    /**
     * {@inheritdoc}
     */
    public function makeRequest($curl, $options)
    {
        $curl->setopt(CURLOPT_URL, $this->url);
        $curl->setopt(CURLOPT_POST, 1);
        $curl->setopt(CURLOPT_POSTFIELDS, $this->requestData);
        $curl->setopt(CURLOPT_CUSTOMREQUEST, "PUT");
        $curl->setoptArray($options);
        $this->curlExec($curl);
    }
}
