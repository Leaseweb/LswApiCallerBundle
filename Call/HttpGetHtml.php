<?php
namespace Lsw\ApiCallerBundle\Call;

use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpGetHtml extends LegacyCurlCall implements ApiCallInterface
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
    public function parseResponseData()
    {
        $this->responseObject = $this->responseData;
    }

    /**
     * {@inheritdoc}
     */
    public function setCurlOptions($options = array())
    {
        $params = array();
        $params['cookie'] = $this->cookie;

        return parent::setCurlOptions(array_merge($params, $options));
    }

}
