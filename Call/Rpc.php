<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * RPC based API call
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
class Rpc extends CurlCall implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    protected function setCurlOptions($options = array())
    {
        $params = array();
        $params['url'] = $this->url;
        $params['post'] = 1;
        $params['postfields'] = xmlrpc_encode_request($this->command, $this->requestObject);

        return parent::setCurlOptions(array_merge($params, $options));
    }
}
