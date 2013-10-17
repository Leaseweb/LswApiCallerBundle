<?php
namespace Lsw\ApiCallerBundle\Call;

use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
abstract class CurlCall implements ApiCallInterface
{
    protected $url;
    protected $name;
    protected $requestData;
    protected $requestObject;
    protected $responseData;
    protected $responseObject;
    protected $status;
    protected $asAssociativeArray;

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
                0   => 'Connection failed',
                100 => 'Continue',
                101 => 'Switching Protocols',
                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',
                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',
                400 => 'Bad Request',
                401 => 'Unauthorized',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
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
        $options['returntransfer']=true;
        $options = $this->parseCurlOptions($options);
        $this->makeRequest($engine, $options);
        $this->parseResponseData();
        $this->status = $engine->getinfo(CURLINFO_HTTP_CODE);
        $result = $this->getResponseObject();

        return $result;
    }

    /**
     * Private method to parse cURL options from the bundle config.
     * If some option is not defined an exception will be thrown.
     *
     * @param array $config ApiCallerBundle configuration
     *
     * @throws \Exception Specified cURL option can't be found
     *
     * @return array
     */
    protected function parseCurlOptions($config)
    {
        $options = array();
        $prefix = 'CURLOPT_';
        foreach ($config as $key => $value) {
            $constantName = $prefix . strtoupper($key);
            if (!defined($constantName)) {
                $messageTemplate  = "Invalid option '%s' in apicaller.config parameter. ";
                $messageTemplate .= "Use options (from the cURL section in the PHP manual) without prefix '%s'";
                $message = sprintf($messageTemplate, $key, $prefix);
                throw new \Exception($message);
            }
            $options[constant($constantName)] = $value;
        }

        return $options;
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
    public function makeRequest($curl, $options)
    {
        $class = get_class($this);
        throw new \Exception("Class $class must implement method 'makeRequest'. Hint:

        public function makeRequest(\$curl, \$options)
        {
        \$curl->setopt(CURLOPT_URL, \$this->url.'?'.\$this->requestData);
        \$curl->setoptArray(\$options);
        \$this->responseData = \$curl->exec();
        }

        ");
    }

}
