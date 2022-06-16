<?php

namespace Publy\ServiceClient\Api;

use GuzzleHttp\Psr7\Request;
use Publy\ServiceClient\Api\ResponseException;
use GuzzleHttp\Exception\RequestException;

class Http {
    /**
     * Use the send method to call every endpoint except for oauth/tokens
     *
     * @param HttpClient $client
     * @param string     $endPoint E.g. "/tickets.json"
     * @param array      $options
     *                             Available options are listed below:
     *                             array $queryParams Array of unencoded key-value pairs, e.g. ["ids" => "1,2,3,4"]
     *                             array $postFields Array of unencoded key-value pairs, e.g. ["filename" => "blah.png"]
     *                             string $method "GET", "POST", etc. Default is GET.
     *                             string $contentType Default is "application/json"
     *
     * @return mixed The response body, parsed from JSON into an object. Also returns bool or null if something went wrong
     * @throws ResponseException
     */
    public static function send(
        $apiService,
        string $endPoint,
        array $options = [],
        array $headers = []
    ) {
        $options = self::mergeWithDefaultOptions($options);
        $headers = self::mergeWithDefaultHeaders($headers, $options['contentType']);
        $request = self::createRequest($options['method'], $apiService, $endPoint, $headers);

        $requestOptions = [];

        if (! empty($options['timeout'])) {
            $requestOptions['timeout'] = $options['timeout'];
        }

        if (! empty($options['multipart'])) {
            $request                     = $request->withoutHeader('Content-Type');
            $requestOptions['multipart'] = $options['multipart'];
        } elseif (! empty($options['postFields'])) {
            $request = $request->withBody(\GuzzleHttp\Psr7\stream_for(json_encode($options['postFields'])));
        } elseif (! empty($options['file'])) {
            if (is_file($options['file'])) {
                $fileStream = new LazyOpenStream($options['file'], 'r');
                $request    = $request->withBody($fileStream);
            }
        }

        if (! empty($options['queryParams'])) {
            foreach ($options['queryParams'] as $queryKey => $queryValue) {
                $uri     = $request->getUri();
                $uri     = $uri->withQueryValue($uri, $queryKey, urlencode($queryValue));
                $request = $request->withUri($uri, true);
            }
        }

        try {
            //list ($request, $requestOptions) = $client->getAuth()->prepareRequest($request, $requestOptions);
            $response = $apiService->guzzle->send($request, $requestOptions);
        } catch (RequestException $e) {
            throw new ResponseException($e, $options);
        } 

        if (isset($file)) {
            fclose($file);
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param array $options
     * @return array|string[]
     */
    private static function mergeWithDefaultOptions(array $options): array
    {
        return array_merge([
            'method' => 'GET',
            'contentType' => 'application/json',
            'postFields' => null,
            'queryParams' => null
        ],
            $options
        );
    }

    /**
     * @param array $headers
     * @param $contentType
     * @return array|string[]
     */
    private static function mergeWithDefaultHeaders(array $headers, $contentType): array
    {
        return array_merge([
            'Accept' => 'application/json',
            'Content-Type' => $contentType
        ],
            $headers
        );
    }

    /**
     * @param $method
     * @param $apiService
     * @param string $endPoint
     * @param array $headers
     * @return Request
     */
    private static function createRequest($method, $apiService, string $endPoint, array $headers): Request
    {
        return new Request(
            $method,
            $apiService->getApiUrl() . $endPoint,
            $headers
        );
    }
}