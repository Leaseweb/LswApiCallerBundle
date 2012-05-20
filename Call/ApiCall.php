<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
abstract class ApiCall implements ApiCallInterface
{
  protected $url;
  protected $name;
  protected $requestData;
  protected $requestObject;
  protected $responseData;
  protected $responseObject;
  protected $status;
  
  public function __construct($url,$requestObject)
  {
    $this->url = $url;
    $this->requestObject = $requestObject;
  }
  
  public function getUrl()
  {
    return $this->url;
  }

  public function getName()
  {
    return get_class($this);
  }
  
  public function getRequestData()
  {
    return $this->requestData;
  }
  
  public function getRequestObject()
  {
    return $this->requestObject;
  }
  
  public function getRequestObjectRepresentation()
  {
    $dumper = new \Symfony\Component\Yaml\Dumper();
    return $dumper->dump(json_decode(json_encode($this->requestObject),true),100);
  }
  
  public function getResponseData()
  {
    return $this->responseData;
  }
  
  public function getResponseObject()
  {
    return $this->responseObject;
  }
  
  public function getResponseObjectRepresentation()
  {
    $dumper = new \Symfony\Component\Yaml\Dumper();
    return $dumper->dump(json_decode(json_encode($this->responseObject),true),100);
  }
  
  public function getStatus()
  {
    return $this->status;
  }
  
  public function generateRequestData()
  {
    $class = get_class($this);
    throw new \Exception("Class $class must implement method 'generateRequestData'. Hint:
    
    public function generateRequestData()
    { 
    \$this->requestData = http_build_query(\$this->requestObject);
    }
    
    ");
  }  

  public function parseResponseData()
  {
    $class = get_class($this);
    throw new \Exception("Class $class must implement method 'parseResponseData'. Hint:
    
    public function parseResponseData()
    { 
    \$this->responseObject = json_decode(\$this->responseData);
    }
    
    ");
  }

  public function makeRequest($curlHandle, $options)
  { 
  	$class = get_class($this);
    throw new \Exception("Class $class must implement method 'makeRequest'. Hint:
    
    public function makeRequest(\$curlHandle, \$options)
    { 
    curl_setopt(\$curlHandle, CURLOPT_URL, \$this->url.'?'.\$this->requestData);
    curl_setopt_array(\$curlHandle, \$options);
    \$this->responseData = curl_exec(\$curlHandle);
    }
    
    ");
  }
  
}