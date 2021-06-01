<?php

namespace Publy\ServiceClient\Api;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;

class BaseApiServiceWithHeader
{

    public $guzzle;

    protected $domain;
    protected $apiUrl;

    protected $timeout;
    protected $maxRetryCount;

    public function __construct()
    {
        // https://gist.github.com/gunnarlium/665fc1a2f6dd69dfba65
        $stack = \GuzzleHttp\HandlerStack::create(new \GuzzleHttp\Handler\CurlHandler());
        $stack->push(\GuzzleHttp\Middleware::retry($this->createRetryHandler()));
        $this->guzzle = new \GuzzleHttp\Client([
            'handler' => $stack,
        ]);

        $this->timeout = 3000;
        $this->maxRetryCount = 0;
    }

    function createRetryHandler()
    {
        return function (
            $retries,
            Psr7Request $request,
            Psr7Response $response = null,
            RequestException $exception = null
        ) {

            if ($retries >= $this->maxRetryCount) {
                return false;
            }

            if (!($this->isServerError($response) || $this->isConnectError($exception))) {
                return false;
            }

            return true;
        };
    }

    /**
     * @param Psr7Response $response
     * @return bool
     */
    function isServerError(Psr7Response $response = null)
    {
        return $response && $response->getStatusCode() >= 500;
    }

    /**
     * @param RequestException $exception
     * @return bool
     */
    function isConnectError(RequestException $exception = null)
    {
        return $exception instanceof ConnectException;
    }

    /**
     * Returns the generated api URL
     *
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param $endpoint
     * @param $options
     * @return mixed
     * @throws ResponseException
     */
    public function get($endpoint, $queryParams, $headers)
    {
        $headers = array_merge([
            'Accept' => 'application/json',
        ], $headers);

        return HttpWithHeader::send(
            $this,
            $endpoint,
            [
                'method' => 'GET',
                'queryParams' => $queryParams,

                'timeout' => $this->timeout
            ],
            $headers
        );
    }

    /**
     * @param $endpoint
     * @param $options
     * @return mixed
     * @throws ResponseException
     */
    public function post($endpoint, $postData, $optionalHeaders)
    {
        $headers = array_merge([
            'Accept' => 'application/json',
        ], $optionalHeaders);

        return HttpWithHeader::send(
            $this,
            $endpoint,
            [
                'method' => 'POST',
                'postFields' => $postData,
                'timeout' => $this->timeout
            ],
            $headers
        );
    }
}