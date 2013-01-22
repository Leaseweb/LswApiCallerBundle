<?php
namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;
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
        if ($this->logger) {
            $this->logger->startCall($call);
        }
        $this->lastCall = $call;
        $result = $call->execute($this->options);
        if ($this->logger) {
            $this->logger->stopCall($call);
        }

        return $result;
    }

}
