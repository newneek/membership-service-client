<?php

namespace Publy\ServiceClient\Api;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Response as Psr7Response;

class BaseApiService
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
     * @param array $queryParams
     * @return mixed
     * @throws ResponseException
     */
    public function get($endpoint, $queryParams = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            [
                'queryParams' => $queryParams,
                'timeout' => $this->timeout
            ]
        );

        return $response;
    }

    /**
     * This is a helper method to do a post request.
     *
     * @param       $endpoint
     * @param array $postData
     *
     * @return array
     * @throws ResponseException
     */
    public function post($endpoint, $postData = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            [
                'postFields' => $postData,
                'method' => 'POST',
                'timeout' => $this->timeout
            ]
        );

        return $response;
    }

    /**
     * This is a helper method to do a put request.
     *
     * @param       $endpoint
     * @param array $putData
     *
     * @return array
     * @throws ResponseException
     */
    public function put($endpoint, $putData = [])
    {
        $response = Http::send(
            $this,
            $endpoint,
            [
                'postFields' => $putData,
                'method' => 'PUT',
                'timeout' => $this->timeout
            ]
        );

        return $response;
    }

    /**
     * This is a helper method to do a delete request.
     *
     * @param $endpoint
     *
     * @return array
     * @throws ResponseException
     */
    public function delete($endpoint)
    {
        $response = Http::send(
            $this,
            $endpoint,
            [
                'method' => 'DELETE',
                'timeout' => $this->timeout
            ]
        );

        return $response;
    }

    /**
     * @param $endpoint
     * @param $queryParams
     * @param $headers
     * @return mixed
     * @throws ResponseException
     */
    public function getWithHeader($endpoint, $queryParams, $headers)
    {
        return Http::send(
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
     * @param $postData
     * @param $headers
     * @return mixed
     * @throws ResponseException
     */
    public function postWithHeader($endpoint, $postData, $headers)
    {
        return Http::send(
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