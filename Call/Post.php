<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as POST parameters
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Post extends CurlCall implements ApiCallInterface
{
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
