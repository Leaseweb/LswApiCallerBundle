<?php

namespace Lsw\ApiCallerBundle\Logger;

use Lsw\ApiCallerBundle\Call\ApiCallInterface;

/**
 * Interface for API call loggers
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
interface ApiCallLoggerInterface
{
    /**
     * Logs an API call.
     *
     * @param ApiCallInterface $call The API call
     *
     * @return void
     */
    public function startCall(ApiCallInterface $call);

    /**
     * Mark the last started call as stopped and register the response.
     * This is used for timing of calls and registering reponse data.
     *
     * @param ApiCallInterface $call The API call
     *
     * @return void
     */
    public function stopCall(ApiCallInterface $call);
}
