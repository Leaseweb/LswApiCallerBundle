<?php
namespace Lsw\ApiCallerBundle\Call;

use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpGetHtml extends CurlCall implements ApiCallInterface
{
    /**
     * ApiCall class constructor
     *
     * @param string $url           API url
     * @param object $cookie        Cookie
     * @param object $requestObject Request
     */
    public function __construct($url,$cookie,$requestObject=null)
    {
        $this->url = $url;
        $this->cookie = $cookie;
        $this->requestObject = $requestObject;
    }

    /**
     * {@inheritdoc}
     */
    public function generateRequestData()
    {
        if ($this->requestObject) {
                $this->requestData = http_build_query($this->requestObject);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponseData()
    {
        $this->responseObject = $this->responseData;
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($curl, $options)
    {
        $url = $this->url;
        if ($this->requestData) {
                $url.= $this->requestData;
        }
        $curl->setopt(CURLOPT_URL, $url);
        $curl->setopt(CURLOPT_COOKIE, $this->cookie);
        $curl->setoptArray($options);
        $this->responseData = $curl->exec();
    }

}
