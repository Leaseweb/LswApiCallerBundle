<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * Interface of cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
interface ApiCallInterface
{
    public function __construct($url, $method, $requestObject);

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
     */
    public function getRequestData();

    /**
     * Get the request parameter data as PHP object
     */
    public function getRequestObject();

    /**
     * Get the request request headers data as string
     */
    public function getRequestHeaders();

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
     * Get the request response headers data as string
     */
    public function getResponseHeaders();

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
     * @param array     $options    Array of options
     * @param callable  $parser     Parser for the returned data
     *
     * @return mixed Response
     */
    public function execute(array $options = array(), $parser = null);

}
