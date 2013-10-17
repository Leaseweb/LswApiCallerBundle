<?php
namespace Lsw\ApiCallerBundle\Call;

use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * Soap based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
abstract class SoapCall implements ApiCallInterface
{
    protected $url;
    protected $name;
    protected $requestData;
    protected $requestObject;
    protected $responseData;
    protected $responseObject;
    protected $status;
    protected $asAssociativeArray;

    protected $call;

    /**
     * Class constructor
     *
     * @param string $url                API url
     * @param object $requestObject      Request
     * @param bool   $asAssociativeArray Return associative array
     */
    public function __construct($url,$requestObject,$asAssociativeArray=false)
    {
        $this->url = $url;
        $this->requestObject = $requestObject;
        $this->asAssociativeArray = $asAssociativeArray;
        $this->generateRequestData();
    }

    /**
     * Set call name
     *
     * @param object $call Name of the call
     */
    public function setCall($call)
    {
        $this->call = $call;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return get_class($this);
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestObject()
    {
        return $this->requestObject;
    }

    /**
     * {@inheritdoc}
     */
    public function getRequestObjectRepresentation()
    {
        $dumper = new \Symfony\Component\Yaml\Dumper();

        return $dumper->dump(json_decode(json_encode($this->requestObject), true), 100);
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseObject()
    {
        return $this->responseObject;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseObjectRepresentation()
    {
        $dumper = new \Symfony\Component\Yaml\Dumper();

        return $dumper->dump(json_decode(json_encode($this->responseObject), true), 100);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Get the HTTP status message by HTTP status code
     *
     * @return mixed HTTP status message (string) or the status code (integer) if the message can't be found
     */
    public function getStatus()
    {
        $code = $this->getStatusCode();
        $codes = array(
                200 => 'OK',
                500 => 'Error',
        );

        if (isset($codes[$code])) {
            return "$code $codes[$code]";
        }

        return $code;
    }

    /**
     * Execute the call
     *
     * @param array  $options      Array of options
     * @param object $engine       Calling engine
     * @param bool   $freshConnect Make a fresh connection every call
     *
     * @return mixed Response
     */
    public function execute($options, $engine, $freshConnect = false)
    {
        $this->makeRequest($engine, $options);
        $this->parseResponseData();
        $result = $this->getResponseObject();

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function generateRequestData()
    {
        $class = get_class($this);
        throw new \Exception("Class $class must implement method 'generateRequestData'. Hint:

        public function generateRequestData()
        {
        \$this->requestData = http_build_query(\$this->requestObject);
        }

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponseData()
    {
        $class = get_class($this);
        throw new \Exception("Class $class must implement method 'parseResponseData'. Hint:

        public function parseResponseData()
        {
        \$this->responseObject = json_decode(\$this->responseData,\$this->asAssociativeArray);
        }

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($soap)
    {
        $class = get_class($this);
        throw new \Exception("Class $class must implement method 'makeRequest'. Hint:

        public function makeRequest(\$soap)
        {
        \$soap->__setLocation(\$this->url);
        \$this->responseData = json_encode(\$soap->__call(\$this->call, array(\$this->requestObject)));
        }

        ");
    }

}
