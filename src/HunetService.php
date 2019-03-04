<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use GuzzleHttp\Psr7\Request;

class HunetService extends BaseApiService {

    protected $apiKey;

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
        $this->apiToken = env('HUNET_API_TOKEN');
    }

    public function viewContent($hunetId, $contentId, $setId)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'content_id' => $contentId,
            'set_id' => $setId,
            'com_id' => 'publy'
        ];
        $properties = json_encode($properties);
        $queryParams = ['event' => 'pageview_chapter', 'properties' => $properties];

        $request = $this->getRequest('hunet_test', $queryParams);

        while ($retryCount > 0) {
            try {
                $response = $this->guzzle->send($request);
                return respond_success(json_decode($response->getBody(), true));
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function completeContent($hunetId, $setId, $contentId)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'content_id' => $contentId,
            'set_id' => $setId,
            'com_id' => 'publy'
        ];
        $queryParams = ['event' => 'complete_chapter', 'properties' => json_encode($properties)];

        $request = $this->getRequest('hunet_test', $queryParams);

        while ($retryCount > 0) {
            try {
                $response = $this->guzzle->send($request);
                return respond_success(json_decode($response->getBody(), true));
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function rateSet($hunetId, $setId, $rating)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'set_id' => $setId,
            'rating' => $rating,
            'com_id' => 'publy'
        ];
        $queryParams = ['event' => 'rate_set', 'properties' => json_encode($properties)];

        $request = $this->getRequest('hunet_test', $queryParams);

        while ($retryCount > 0) {
            try {
                $response = $this->guzzle->send($request);
                return respond_success(json_decode($response->getBody(), true));
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function addBookmark($hunetId, $setId)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'set_id' => $setId,
            'com_id' => 'publy'
        ];
        $queryParams = ['event' => 'bookmark', 'properties' => json_encode($properties)];

        $request = $this->getRequest('hunet_test', $queryParams);

        while ($retryCount > 0) {
            try {
                $response = $this->guzzle->send($request);
                return respond_success(json_decode($response->getBody(), true));
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    private function getRequest($endPoint, $queryParams)
    {
        $headers =
            [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '. $this->apiToken
            ];

        $request = new Request(
            'GET',
            $this->getApiUrl() . $endPoint,
            $headers
        );

        foreach ($queryParams as $queryKey => $queryValue) {
            $uri = $request->getUri();
            $uri = $uri->withQueryValue($uri, $queryKey, urlencode($queryValue));
            $request = $request->withUri($uri, true);
        }

        return $request;
    }
}