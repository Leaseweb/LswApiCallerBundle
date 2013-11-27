<?php
namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;
use Lsw\ApiCallerBundle\Call\ApiCallInterface;
use Lsw\ApiCallerBundle\Call\ApiCallFactory;
use Lsw\ApiCallerBundle\Parser\ApiParserFactory;

/**
 * Logging API Caller
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class LoggingApiCaller implements ApiCallerInterface
{
    protected $urlPrefix, $parser, $nextCallParser;
    private $options;
    private $logger;
    private $lastCall;
    private $engine;

    /**
     * Constructor creates dependency objects
     *
     * @param array                  $options Options array
     * @param ApiCallLoggerInterface $logger  Logger
     *
     */
    public function __construct($options, ApiCallLoggerInterface $logger = null)
    {
        $this->options = $options;
        $this->logger = $logger;
        $this->freshConnect = isset($this->options['fresh_connect']) ? $this->options['fresh_connect'] : false;
    }

    public function __call($name, array $arguments)
    {
        if(substr($name, -4) == 'Call') {
            $method = substr($name, 0, -4);

            $call = ApiCallFactory::get($method, $arguments);

            $this->logger->startCall($call);
            $result = $call->execute($this->options);
            $this->logger->stopCall($call);
        }
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

    /**
     * Set URL prefix
     *
     * @param string $prefix
     *
     */
    public function setUrlPrefix($prefix)
    {
        $this->urlPrefix = $prefix;
    }

    /**
     * Set default parser
     *
     * @param string $parser
     *
     */
    public function setParser($parser)
    {
        $this->parser = ApiParserFactory::get($parser);
    }

    /**
     * Set parser for the next call only
     *
     * @param string $parser
     *
     */
    public function setNextCallParser($parser)
    {
        $this->nextCallParser = ApiParserFactory::get($parser);
    }

}
