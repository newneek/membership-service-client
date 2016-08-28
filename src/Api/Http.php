<?php

namespace Publy\ServiceClient;

use GuzzleHttp\Psr7\Request;
use Api\ResponseException;
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
     * @throws AuthException
     */
    public static function send(
        $apiService,
        $endPoint,
        $options = []
    ) {
        $options = array_merge([
                'method'      => 'GET',
                'contentType' => 'application/json',
                'postFields'  => null,
                'queryParams' => null
            ],
            $options
        );

        $headers = [
            'Accept'       => 'application/json',
            'Content-Type' => $options['contentType']
        ];

        $request = new Request(
            $options['method'],
            $apiService->getApiUrl() . $endPoint,
            $headers
        );

        $requestOptions = [];

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
                $uri     = $uri->withQueryValue($uri, $queryKey, $queryValue);
                $request = $request->withUri($uri, true);
            }
        }

        try {
            //list ($request, $requestOptions) = $client->getAuth()->prepareRequest($request, $requestOptions);
            $response = $apiService->guzzle->send($request, $requestOptions);
        } catch (RequestException $e) {
            throw new ResponseException($e);
        } 

        if (isset($file)) {
            fclose($file);
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}