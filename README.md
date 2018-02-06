LswApiCallerBundle
==================

![screenshot](http://www.leaseweblabs.com/wp-content/uploads/2013/01/api_caller.png)

The LswApiCallerBundle adds a CURL API caller to your Symfony2 application.
It is easy to use from the code and is aimed to have full debugging capabilities.

[Read the LeaseWebLabs blog about LswApiCallerBundle](http://www.leaseweblabs.com/2013/01/symfony2-bundle-for-curl-api-calling/)


## Requirements

* PHP 5.3 with curl support
* Symfony >= 2.1

## Installation

Installation is broken down in the following steps:

1. Download LswApiCallerBundle using composer
2. Enable the Bundle
3. Make sure the cURL module in PHP is enabled

### Step 1: Download LswApiCallerBundle using composer

Add LswApiCallerBundle in your composer.json:

```js
{
    "require": {
        "leaseweb/api-caller-bundle": "*",
        ...
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update leaseweb/api-caller-bundle
```

Composer will install the bundle to your project's `vendor/leaseweb` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Lsw\ApiCallerBundle\LswApiCallerBundle(),
    );
}
```

### Step 3: Make sure the cURL module in PHP is enabled

On a Debian based distribution (like Ubuntu) the package is called "php5-curl" and
can be installed using the following commands:

``` bash
$ sudo apt-get install php5-curl
$ sudo service apache2 restart
```

On a RedHat based distribution (like CentOS) the package is called "php-curl" and
can be installed using the following commands:

``` bash
$ sudo yum install php-curl
$ sudo service httpd restart
```

To check this create and run a PHP file with the following contents:

``` php
<?php phpinfo() ?>
```

It should display that the option "cURL support" is set to "enabled".

This package should work on a Windows installation as well provided the CURL support
is enabled in PHP.

## Usage

You can use the caller by getting the service "api_caller" and using the "call" function with one of
the available call types:

- HttpGetJson
- HttpPostJson
- HttpPostJsonBody (the body will be json_encode)
- HttpPutJson
- HttpDeleteJson
- HttpGetHtml

Example of usage with the "HttpGetJson" call type:

``` php

use Symfony\Bundle\FrameworkBundle\Controller\Controller
use Lsw\ApiCallerBundle\Call\HttpGetJson;

class SomeController extends Controller
{
    public function someAction()
    {
        ...
        $output = $this->get('api_caller')->call(new HttpGetJson($url, $parameters));
        ...
    }
}

```

Example of usage with the "HttpPostJsonBody" call type:

``` php

use Symfony\Bundle\FrameworkBundle\Controller\Controller
use Lsw\ApiCallerBundle\Call\HttpPostJsonBody;

class SomeController extends Controller
{
    public function someAction()
    {
        ...
        $arrayToPost = array(
            'ip_address' => array(
                'name' => 'IpName',
                'hostname' => 'HostName',
                'ip' => '192.168.0.1',
                'throttling_template' => array(
                    'name' => 'Throttling Template'
                )
            )
        ); // this will be json_encode. If you don't want to json_encode, use HttpPostJson instead of HttpPostJsonBody
        $output = $this->get('api_caller')->call(new HttpPostJsonBody($url, $arrayToPost, true, $parameters)); // true to have an associative array as answer
        ...
    }
}

```

## Configuration

By default it uses these cURL options:
``` yaml
parameters:
    api_caller.options:
        timeout: 10  # maximum transport + execution duration of the call in sec.
        ssl_verifypeer: false  # to stop cURL from verifying the peer's certificate.
        useragent: "LeaseWeb API Caller"  # contents of the "User-Agent: " header.
        followlocation: true  # to follow any "Location: " header that the server sends.
        sslversion: 3  # set to 3 to avoid any bugs that relate to automatic version selection.
        fresh_connect: false  # set to true to force full reconnect every call.
```

## License

This bundle is under the MIT license.

The "wall-socket" icon in the web debug toolbar is part of the Picas icon set (official website: http://www.picasicons.com).
The icon is licensed and may only be used to identifying the LswApiCallerBundle in the Symfony2 web debug toolbar.
All ownership and copyright of this icon remain the property of Rok Benedik.
