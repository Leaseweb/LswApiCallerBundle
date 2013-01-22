<?php

namespace Lsw\ApiCallerBundle\Logger;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Lsw\ApiCallerBundle\Call\ApiCallInterface;

/**
 * Logs Executed API calls in an array
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class ApiCallLogger implements ApiCallLoggerInterface
{
    protected $logger;

    /**
     * Constructor.
     *
     * @param LoggerInterface $logger A LoggerInterface instance
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @var array $calls Executed API calls.
     */
    public $calls = array();

    public $start = null;

    public $currentCall = 0;

    /**
     * {@inheritdoc}
     */
    public function startCall(ApiCallInterface $call)
    {
        $this->start = microtime(true);
        $this->calls[++$this->currentCall] = array(
            'type' => $call->getName(),
            'url' => $call->getUrl(),
            'requestData' => $call->getRequestData(),
            'requestObject' => $call->getRequestObjectRepresentation(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function stopCall(ApiCallInterface $call)
    {
        $status = $call->getStatus();
        $responseData = $call->getResponseData();
        $responseObject = $call->getResponseObjectRepresentation();
        $executionMS = microtime(true) - $this->start;
        $this->calls[$this->currentCall]+=compact('status', 'responseData', 'responseObject', 'executionMS');

        if (null !== $this->logger) {
        $type = $this->calls[$this->currentCall]['type'];
        $url = $this->calls[$this->currentCall]['url'];
        $requestData = $this->calls[$this->currentCall]['requestData'];
        $responseDataLength = strlen($responseData);
        $executionMS = sprintf('%0.2f', $executionMS * 1000);
        $this->logger->debug("API call \"$type\" requested \"$url?$requestData\" that returned \"$status\" in $executionMS ms sending $responseDataLength bytes");
        }

    }
}

