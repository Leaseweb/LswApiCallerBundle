<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * Interface of cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
interface ApiCallInterface
{
  public function getUrl();
  public function getName();
  public function getRequestData();
  public function getRequestObject();
  public function getRequestObjectRepresentation();
  public function getResponseData();
  public function getResponseObject();
  public function getResponseObjectRepresentation();
  public function getStatus();
  public function generateRequestData();
  public function parseResponseData();
  public function makeRequest($curlHandle, $options);
  
}