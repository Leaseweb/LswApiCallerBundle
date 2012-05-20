<?php
namespace Lsw\ApiCallerBundle\Caller;

use Lsw\ApiCallerBundle\Logger\ApiCallLoggerInterface;
use Lsw\ApiCallerBundle\Call\ApiCallInterface;

/**
 * cURL based API Caller
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class CurlApiCaller implements ApiCallerInterface
{
  private $options;
  private $logger;
  
  public function __construct($options, ApiCallLoggerInterface $logger = null)
  {
    $options['returntransfer']=true;
    $this->options = self::parseCurlOptions($options);
    $this->logger = $logger;
    if (!function_exists('curl_init'))
    {
      $class = get_class($this);
      throw new \Exception("Class '$class' depends on the PHP cURL library that is currently not installed");
    }
  }
  
  /**
   * {@inheritdoc}
   */
  public function call(ApiCallInterface $call)
  { 
  	$curlHandle = curl_init();
    $call->generateRequestData();
    if ($this->logger) $this->logger->startCall($call);
    $call->makeRequest($curlHandle, $this->options);
    $call->parseResponseData();
    $status = self::getStatus(curl_getinfo($curlHandle, CURLINFO_HTTP_CODE));
    if ($this->logger) $this->logger->stopCall($call,$status);
    curl_close($curlHandle);  
    return $call->getResponseObject();
  }
  
  static function parseCurlOptions($config)
  {
    $options = array();
    foreach ($config as $key=>$value)
    {
      $prefix = 'CURLOPT_';
      if (!defined($prefix.strtoupper($key)))
      {
        throw new \Exception("Invalid option '$key' in apicaller.config parameter. Use options (from the cURL section in the PHP manual) without prefix '$prefix'");
      }
      $options[constant('CURLOPT_'.strtoupper($key))]=$value;
    }
    return $options;
  }
  
  static function getStatus($code)
  {
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
    if (isset($codes[$code])) return "$code $codes[$code]";
    return $code;
  }
}