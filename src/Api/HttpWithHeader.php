<?php

namespace Publy\ServiceClient\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class HttpWithHeader
{
    /**
     * Use the send method to call every endpoint except for oauth/tokens
     *
     * @param HttpClient $client
     * @param string $endPoint E.g. "/tickets.json"
     * @param array $options
     *                             Available options are listed below:
     *                             array $queryParams Array of unencoded key-value pairs, e.g. ["ids" => "1,2,3,4"]
     *                             array $postFields Array of unencoded key-value pairs, e.g. ["filename" => "blah.png"]
     *                             string $method "GET", "POST", etc. Default is GET.
     *                             string $contentType Default is "application/json"
     * @param array $headers
     *
     * @return mixed The response body, parsed from JSON into an object. Also returns bool or null if something went wrong
     * @throws ResponseException
     */
    public static function send(
        $apiService,
        string $endPoint,
        array $options,
        array $headers
    ) {
        $options = array_merge([
            'method' => 'GET',
            'contentType' => 'application/json',
            'postFields' => null,
            'queryParams' => null
        ],
            $options
        );

        $request = new Request(
            $options['method'],
            $apiService->getApiUrl() . $endPoint,
            $headers
        );

        $requestOptions = [];

        if (!empty($options['timeout'])) {
            $requestOptions['timeout'] = $options['timeout'];
        }

        if (!empty($options['postFields'])) {
            $request = $request->withBody(\GuzzleHttp\Psr7\stream_for(json_encode($options['postFields'])));
        }

        if (!empty($options['queryParams'])) {
            foreach ($options['queryParams'] as $queryKey => $queryValue) {
                $uri = $request->getUri();
                $uri = $uri->withQueryValue($uri, $queryKey, urlencode($queryValue));
                $request = $request->withUri($uri, true);
            }
        }

        try {
            $response = $apiService->guzzle->send($request, $requestOptions);
        } catch (RequestException $e) {
            throw new ResponseException($e, $options);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}