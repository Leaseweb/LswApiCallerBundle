<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as POST parameters
 *
 * @author Andrii Shchurkov <a.shchurkov@leaseweb.com>
 */
class HttpPost extends LegacyCurlCall implements ApiCallInterface
{
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
        $params['url'] = $this->url;
        $params['post'] = 1;
        $params['postfields'] = $this->requestData;

        return parent::setCurlOptions(array_merge($params, $options));
    }
}
