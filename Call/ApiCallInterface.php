<?php
namespace Lsw\ApiCallerBundle\Call;
use Lsw\ApiCallerBundle\Helper\Curl;

/**
 * Interface of cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
interface ApiCallInterface
{
    public function __construct($url,$requestObject,$asAssociativeArray=false);

    /**
     * Get the URL of the call
     */
    public function getUrl();

    /**
     * Get the name of the call
     */
    public function getName();

    /**
     * Get the request parameter data as HTTP Query String
     *
     * @see \Lsw\ApiCallerBundle\Call\ApiCall::generateRequestData()
     */
    public function getRequestData();

    /**
     * Get the request parameter data as PHP object
     */
    public function getRequestObject();

    /**
     * Get the request parameter data object as YML formatted string
     */
    public function getRequestObjectRepresentation();

    /**
     * Get the request response data as HTTP Query String
     */
    public function getResponseData();

    /**
     * Get the request response data as PHP object
     */
    public function getResponseObject();

    /**
     * Get the request response data object as YML formatted string
     */
    public function getResponseObjectRepresentation();

    /**
     * Get the HTTP status of the API call
     */
    public function getStatusCode();

    /**
     * Get the HTTP status of the API call
     */
    public function getStatus();

    /**
     * Execute the call
     *
     * @param array  $options      Array of options
     * @param object $engine       Calling engine
     * @param bool   $freshConnect Make a fresh connection every call
     *
     * @return mixed Response
     */
    public function execute($options, $engine, $freshConnect);

}
