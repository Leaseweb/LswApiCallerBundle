<?php
namespace Lsw\ApiCallerBundle\Call;

use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpDeleteJson extends CurlCall implements ApiCallInterface
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
        $this->responseObject = json_decode($this->responseData,$this->asAssociativeArray);
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($curl, $options)
    {
        $url = $this->url;
        if ( $this->requestData ) {
            $url .= '?' . $this->requestData;
        }
        $curl->setopt(CURLOPT_URL, $url);
        $curl->setopt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        $curl->setoptArray($options);
        $this->curlExec($curl);
    }
}
