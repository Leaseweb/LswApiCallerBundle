<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * Parent class for the legacy curl calls
 *
 * @author Dmitry Parnas <d.parnas@ocom.com>
 */
abstract class LegacyCurlCall extends CurlCall implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    public function setCurlOptions($options = array())
    {
        $params = array();
        $params['url'] = $this->url.'?'.$this->requestData;
        $params['returntransfer'] = true;

        return parent::setCurlOptions(array_merge($params, $options));
    }

    /**
     * {@inheritdoc}
     */
    public function execute($options)
    {
        $result = parent::execute($options);
        $this->parseResponseData();

        return $result;
    }

    abstract public function parseResponseData();
}
