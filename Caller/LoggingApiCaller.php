<?php
namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;

use Lsw\ApiCallerBundle\Call\ApiCallInterface;
use Lsw\ApiCallerBundle\Parser\ApiParserInterface;

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

    protected $options;
    protected $logger;
    protected $lastCall;

    /**
     * Constructor creates dependency objects
     *
     * @param array                  $options Options array
     * @param ApiCallLoggerInterface $logger  Logger
     *
     */
    public function __construct($options, ApiCallLoggerInterface $logger = null, ApiParserInterface $parser = null)
    {
        $this->endpoint = $options['endpoint'];

        if(!$parser) {
            $parser = ParserFactory::get($options['format']);
        }

        $this->parser = $parser;
        $this->logger = $logger;
        $this->options = $options;
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

            //$arguments[0] is command or url
            $arguments[0] = $this->urlify($arguments[0]);

            $call = CallFactory::get($method, $arguments);

            return $this->call($call);
        }
    }

    /**
     *
     * @param string $command command to urlify
     *
     * @return string command
     */
    protected function urlify($command)
    {
        if(!filter_var($command, FILTER_VALIDATE_URL)){
            $command = $this->endpoint.$command;
        }

        return $command;
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
        $result = $call->execute($this->options['engine'], $this->parser);
        if ($this->logger) {
            $this->logger->stopCall($call);
        }

        return $result;
    }
}
