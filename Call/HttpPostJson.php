<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as POST parameters
 * Response is parsed as JSON
 *
 * @author Maurits van der Schee <m.vanderschee@leaseweb.com>
 */
class HttpPostJson extends HttpPost implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    public function parseResponseData()
    {
        $this->responseObject = json_decode($this->responseData,$this->asAssociativeArray);
    }
}
