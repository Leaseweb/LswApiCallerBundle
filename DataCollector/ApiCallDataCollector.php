<?php
namespace Lsw\ApiCallerBundle\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;

/**
 * ApiCallDataCollector
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class ApiCallDataCollector extends DataCollector
{
    private $logger;

    /**
     * Class constructor
     *
     * @param ApiCallLoggerInterface $logger Logger object
     */
    public function __construct(ApiCallLoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        $this->data = array(
            'calls'        => null !== $this->logger ? $this->logger->calls : array(),
        );
    }

    /**
     * Method counts amount of HTTP statuses, which is not equals to "200 OK"
     *
     * @return number
     */
    public function getReturnedErrorCount()
    {
        $errors = 0;
        foreach ($this->data['calls'] as $call) {
            $errors += $call['status']!='200 OK'?1:0;
        }

        return $errors;
    }

    /**
     * Method returns amount of logged API calls
     *
     * @return number
     */
    public function getCallCount()
    {
        return count($this->data['calls']);
    }

    /**
     * Method returns all logged API call objects
     *
     * @return mixed
     */
    public function getCalls()
    {
        return $this->data['calls'];
    }

    /**
     * Method calculates API calls execution time
     *
     * @return number
     */
    public function getTime()
    {
        $time = 0;
        foreach ($this->data['calls'] as $call) {
            $time += $call['executionMS'];
        }

        return $time;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'api';
    }
}
