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
      'calls'    => null !== $this->logger ? $this->logger->calls : array(),
    );
  }

  public function getReturnedErrorCount()
  {
    $errors = 0;
    foreach ($this->data['calls'] as $call)
    {
      $errors += $call['status']!='200 OK'?1:0;
    }
    return $errors;
  }
  
  public function getCallCount()
  {
    return count($this->data['calls']);
  }

  public function getCalls()
  {
    return $this->data['calls'];
  }

  public function getTime()
  {
    $time = 0;
    foreach ($this->data['calls'] as $call)
    {
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