<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as DELETE parameters
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Delete extends Post implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    public function setCurlOptions($options = array())
    {
        $params = array();
        $params['customrequest'] = 'delete';

        return parent::setCurlOptions(array_merge($params, $options));
    }
}
