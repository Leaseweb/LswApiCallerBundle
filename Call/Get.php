<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Get extends CurlCall implements ApiCallInterface
{

    /**
     * {@inheritdoc}
     */
    public function setCurlOptions($options = array())
    {
        $params = array();
        $params['url'] = $this->url.$this->command.'?'.$this->requestData;

        return parent::setCurlOptions(array_merge($params, $options));
    }

}
