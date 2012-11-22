<?php

namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Call\ApiCallInterface;

/**
 * Interface for API Caller
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
interface ApiCallerInterface
{
  /**
   * Execute an API call using the HTTP GET method
   *
   * @param string ApiCallInterface The API call
   * @return string The parsed response of the API call 
   */
  public function call(ApiCallInterface $call);
    
  public function getLastStatus();

}