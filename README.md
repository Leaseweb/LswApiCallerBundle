LswApiCallerBundle
==================

![screenshot](http://www.leaseweblabs.com/wp-content/uploads/2013/01/api_caller.png)

The LswApiCallerBundle adds a CURL API caller to your Symfony2 application.
It is easy to use from the code and is aimed to have full debugging capabilities.

## Requirements

* PHP 5.3 with curl support
* Symfony 2.3

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
```

## Usage

### APIs and configuration

LswApiCaller 2 is built on the concept of separate APIs. To use it you first need to configure API you would like to access in parameters.yml.

Example:

    lsw_api_caller:
        example_api:
            endpoint: http://your.url/maybe/even/with/path/
            format:   json
            engine:
                timeout: 10


"endpoint" is prefix that would be prepended to your api call paths.

"format" is default parser api response should get through. While you can use your custom parsers as well, you can only setup one of the built-in ones from the configuration.

**Hint**: "passthrough" parser will just past unmodified response body back to your application.

"engine" is array of configuration parameters that should be passed to the underlying call engine. All the built-in callers are using curl as their base engine, so "engine" parameters would be passed to curl_setopt (don't use CURLOPT or CURLINFO prefixes, though, they would be added automatically).


### Get your api instance

api_caller service has api() singleton factory method that you can use to get access to your api. It accepts api name (from the configuration) as it first parameter.

    $api = $this->get('api_caller')->api('example_api');


### Making requests

Most of the time you will be using [built-in request types](#request-types) to make your API calls. To do that just prefix Call() function name with request type that you need and pass method or resource name as the first parameter and array of parameters as the second one.

    $api->postCall('pizzas', array('type' => 'margherita', 'crust' => 'thin'));

    $api->getCall('order', array('id' => 522));


### Built-in methods

"get", "post", "put", "delete" - HTTP calls of the corresponding time. Passed "method" will be glued to the endpoint set for this api.

"xmlrpc" - XmlRpc request.

"soap" - Soap request.


### Using full url

It's possible to actually call a resource using the full url. To do this just pass it as the first parameter.

    $api->getCall('http://example.com/', 'order', array('id' => 522);


### "Next call" options and parser

You can change engine options or parser just for the next call using `onetimeEngineOption($name, $value)` or `onetimeParser($parser)` methods respectively. Both of them returns current object, so you can chain them:

    $api->onetimeParser('passthrough')->getCall('weirdMethod');


### Setting up parsers

All the methods that accept parser as a parameter are able to do it in three ways: string, callable instance (preferably implementing "Lsw\ApiCallerBundle\Parser\ApiParserInterface") or closure. In case of instance or closure - they should accept one string parameter (body of the response from the api).


### Using your own request callers

It is possible to use your own request callers that implement "Lsw\ApiCallerBundle\Call\ApiCallInterface". For most of the callers, it makes sense also to extend "Lsw\ApiCallerBundle\Call\CurlCall" as it will do most of the work.

At this time, you can't use your own caller the same way you would use built-in ones (e.g. with the magic *Call method). You will have to first instantiate it directly (by passing it endpoint, method and request parameters) and then pass it into $api->call() method.


### Using api caller without configuration

Why it's strongly suggested to define your api in the configuration file and then use them like desribed earlier, it's still possible to use api caller in the old fashioned way:

    $this->get('api_caller')->getCall('http://some.api.com/stuff', array('parameters' => 'here'));

In the background predefined api named "_" is used to do that.


## License

This bundle is under the MIT license.

The "wall-socket" icon in the web debug toolbar is part of the Picas icon set (official website: http://www.picasicons.com).
The icon is licensed and may only be used to identifying the LswApiCallerBundle in the Symfony2 web debug toolbar.
All ownership and copyright of this icon remain the property of Rok Benedik.
