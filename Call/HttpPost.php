<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as POST parameters
 *
 * @author Andrii Shchurkov <a.shchurkov@leaseweb.com>
 */
class HttpPost extends CurlCall implements ApiCallInterface
{
    /**
    * {@inheritdoc}
    */
    public function generateRequestData()
    {
        $this->requestData = http_build_query($this->requestObject);
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponseData()
    {
        $this->responseObject = $this->responseData;
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($curl, $options)
    {
        $curl->setopt(CURLOPT_URL, $this->url);
        $curl->setopt(CURLOPT_POST, 1);
        $curl->setopt(CURLOPT_POSTFIELDS, $this->requestData);
        $curl->setoptArray($options);
        $this->responseData = $curl->exec();
    }

}
