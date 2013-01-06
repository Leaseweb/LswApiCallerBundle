<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * Interface of cURL based API Call
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
interface ApiCallInterface
{
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
    public function getStatus();

    /**
     * Generate the request parameter data
     */
    public function generateRequestData();

    /**
     * Parse the request response data
     */
    public function parseResponseData();

    /**
     * Method that makes the actual cURL request
     *
     * @param resource $curlHandle cURL handle
     * @param array    $options    cURL options array
     *
     * @see \Lsw\ApiCallerBundle\Call\ApiCall::makeRequest()
     */
    public function makeRequest($curlHandle, $options);

}
