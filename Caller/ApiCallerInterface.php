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
     * Execute an API call using a certain method
     *
     * @param ApiCallInterface $call The API call
     *
     * @return string The parsed response of the API call
     */
    public function call(ApiCallInterface $call);

    /**
     * Method returns last status
     *
     * @return string Last status
     */
    public function getLastStatus();

    /**
     * Manually set an option.  Overrides any configured option.
     *
     * @param string $name The CURLOPT name
     * @param string $value The CURLOPT value to set
     *
     * @return array
     */
    public function setOption($name, $value);

}
