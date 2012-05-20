<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpGetHtml extends ApiCall implements ApiCallInterface
{
  public function __construct($url,$cookie,$requestObject=null)
  {
    $this->url = $url;
    $this->cookie = $cookie;
    $this->requestObject = $requestObject;
  }
  
  public function generateRequestData()
  {
    if ($this->requestObject) $this->requestData = http_build_query($this->requestObject);
  }
  
  public function parseResponseData()
  {
    $this->responseObject = $this->responseData;
  }
  
  public function makeRequest($curlHandle, $options)
  {
    $url = $this->url;
    if ($this->requestData) $url.= $this->requestData;
    curl_setopt($curlHandle, CURLOPT_URL, $url);
    curl_setopt($curlHandle, CURLOPT_COOKIE, $this->cookie);
    curl_setopt_array($curlHandle, $options);
    $this->responseData = curl_exec($curlHandle);
  }
  
}