<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * RPC based API call
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Rpc extends Post implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    public function setCurlOptions($options = array())
    {
        $params = array();
        $params['url'] = $this->url;

        return parent::setCurlOptions(array_merge($params, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function generateRequestData()
    {
        $this->requestData = xmlrpc_encode_request($this->command, $this->requestObject);
    }
}
