<?php
namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;

use Lsw\ApiCallerBundle\Call\ApiCallInterface;

use Lsw\ApiCallerBundle\Factory\CallFactory;
use Lsw\ApiCallerBundle\Factory\ParserFactory;

/**
 * Logging API Caller
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class LoggingApiCaller implements ApiCallerInterface
{
    protected $endpoint;
    protected $parser;
    protected $onetimeParser;

    protected $options;
    protected $engineOptions;
    protected $logger;
    protected $lastCall;

    /**
     * Constructor creates dependency objects
     *
     * @param array                  $options Options array
     * @param ApiCallLoggerInterface $logger  Logger
     * @param callable|string $parser Result parser
     *
     */
    public function __construct($options, ApiCallLoggerInterface $logger = null, $parser = null)
    {
        $this->endpoint = $options['endpoint'];

        $parser = ParserFactory::get($parser ?: $options['format']);

        $this->parser = $parser;
        $this->logger = $logger;
        $this->options = $options;

        $this->resetEngineOptions();
    }

    /**
     * Execute an API call using a *Call method
     *
     * @return string The parsed response of the API call
     */
    public function __call($name, array $arguments)
    {
        if(substr($name, -4) == 'Call') {
            $method = substr($name, 0, -4);

            $url = $this->endpoint;

            if(isset($arguments[0])) {
                // to allow to pass and call direct url
                if(filter_var($arguments[0], FILTER_VALIDATE_URL)){
                    $url = $arguments[0];
                    $arguments[0] = '';
                }
            }

            array_unshift($arguments, $url);

            $call = CallFactory::get($method, $arguments);

            return $this->call($call);
        }
    }

    /**
     * Method returns last status
     *
     * @return string Last status
     */
    public function getLastStatus()
    {
        if($this->lastCall == null) {
            return null;
        }

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
        $result = $call->execute($this->engineOptions, $this->onetimeParser ?: $this->parser);
        if ($this->logger) {
            $this->logger->stopCall($call);
        }

        $this->resetParser();
        $this->resetEngineOptions();

        return $result;
    }

    /**
     * Set result parser for next call
     *
     * @param callable|string $parser Result parser
     *
     * @return self
     */
    public function onetimeParser($parser)
    {
        $this->onetimeParser = ParserFactory::get($parser ?: $options['format']);

        return $this;
    }

    /**
     * Reset parser to default
     *
     * @return self
     */
    public function resetParser()
    {
        $this->onetimeParser = null;

        return $this;
    }

    /**
     * Set engine option for the next call
     *
     * @param string $name Option name
     * @param string $value Option value
     *
     * @return self
     */
    public function onetimeEngineOption($name, $value)
    {
        $this->engineOptions[$name] = $value;

        return $this;
    }

    /**
     * Reset engine options to default
     *
     * @return self
     */
    public function resetEngineOptions()
    {
        $this->engineOptions = $this->options['engine'];

        return $this;
    }

}
