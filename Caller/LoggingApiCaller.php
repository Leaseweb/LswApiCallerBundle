<?php
namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Helper\Curl;
use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;
use Lsw\ApiCallerBundle\Call\CurlCall;
use Lsw\ApiCallerBundle\Call\ApiCallInterface;

/**
 * Logging API Caller
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class LoggingApiCaller implements ApiCallerInterface
{
    private $options;
    private $logger;
    private $lastCall;
    private $engine;
    private $freshConnect;

    /**
     * Constructor creates dependency objects
     *
     * @param array                  $options Options array
     * @param ApiCallLoggerInterface $logger  Logger
     *
     * @throws \Exception When the cURL library can't be found
     */
    public function __construct($options, ApiCallLoggerInterface $logger = null)
    {
        $this->options = $options;
        $this->logger = $logger;
        $this->engine = null;
        $this->freshConnect = isset($this->options['fresh_connect']) ? $this->options['fresh_connect'] : false;
    }

    /**
     * Method returns last status
     *
     * @return string Last status
     */
    public function getLastStatus()
    {
        return $this->lastCall->getStatus();
    }

    /**
     * Execute an API call using a certain method
     *
     * @param ApiCallInterface $call The API call
     *
     * @return string The parsed response of the API call
     */
    public function call(ApiCallInterface $call)
    {
        if ($call instanceof CurlCall) {
            if ($this->freshConnect || $this->engine == null || !($this->engine instanceof Curl)) {
                $this->engine = new Curl();
            }
        } else {
            if ($this->freshConnect || $this->engine == null || !($this->engine instanceof \SoapClient)) {
                $this->engine = new \SoapClient($call->getUrl());
            }
        }

        if ($this->logger) {
            $this->logger->startCall($call);
        }
        $this->lastCall = $call;
        $result = $call->execute($this->options, $this->engine, $this->freshConnect);
        if ($this->logger) {
            $this->logger->stopCall($call);
        }

        if ($call instanceof CurlCall) {
            if ($this->freshConnect) {
                $this->engine->close();
            }
        }

        return $result;
    }

}
