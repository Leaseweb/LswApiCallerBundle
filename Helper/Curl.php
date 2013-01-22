<?php
namespace Lsw\ApiCallerBundle\Helper;

use Doctrine\Common\Util\Inflector;

/**
 * Class to encapsulate PHP cUrl (curl_xxx) functions for unit tests
 */
class Curl
{
    private $handle;

    /**
     * Constructor stores cUrl handle in object
     *
     * @throws \Exception when php curl library is not installed
     */
    public function __construct()
    {
        if (!function_exists('curl_init')) {
            $class = get_class($this);
            throw new \Exception("Class '$class' depends on the PHP cURL library that is currently not installed");
        }
        $this->handle = curl_init();
    }

    /**
     * Magic method to execute curl_xxx calls
     *
     * @param string $name      Method name (should be camelized)
     * @param array  $arguments Method arguments
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        $name = Inflector::tableize($name);
        if (function_exists("curl_$name")) {
            array_unshift($arguments, $this->handle);

            return call_user_func_array("curl_$name", $arguments);
        }
        throw new \Exception("Function 'curl_$name' do not exist, see PHP manual.");
    }

}