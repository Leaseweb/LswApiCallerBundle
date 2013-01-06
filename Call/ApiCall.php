<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
abstract class ApiCall implements ApiCallInterface
{
    protected $url;
    protected $name;
    protected $requestData;
    protected $requestObject;
    protected $responseData;
    protected $responseObject;
    protected $status;

    /**
     * Class constructor
     *
     * @param string $url           API url
     * @param object $requestObject Request
     */
    public function __construct($url,$requestObject)
    {
        $this->url = $url;
        $this->requestObject = $requestObject;
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
    public function getStatus()
    {
        return $this->status;
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
        \$this->responseObject = json_decode(\$this->responseData);
        }

        ");
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($curlHandle, $options)
    {
        $class = get_class($this);
        throw new \Exception("Class $class must implement method 'makeRequest'. Hint:

        public function makeRequest(\$curlHandle, \$options)
        {
        curl_setopt(\$curlHandle, CURLOPT_URL, \$this->url.'?'.\$this->requestData);
        curl_setopt_array(\$curlHandle, \$options);
        \$this->responseData = curl_exec(\$curlHandle);
        }

        ");
    }

}
