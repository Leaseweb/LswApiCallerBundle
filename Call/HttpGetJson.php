<?php
namespace Lsw\ApiCallerBundle\Call;

use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpGetJson extends CurlCall implements ApiCallInterface
{
    /**
    * {@inheritdoc}
    */
    public function generateRequestData()
    {
        if ( $this->requestObject ) {
            $this->requestData = http_build_query( $this->requestObject );
            if ( $this->dirtyWay ) {
                $this->requestData = preg_replace( '/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $this->requestData );
            }
        }
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
        $curl->setoptArray($options);
        $this->curlExec($curl);
    }

}
