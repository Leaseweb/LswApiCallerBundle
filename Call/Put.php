<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as PUT parameters
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Put extends Post implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    protected function setCurlOptions($options = array())
    {
        $params = array();
        $params['customrequest'] = 'put';

        return parent::setCurlOptions(array_merge($params, $options));
    }
}
