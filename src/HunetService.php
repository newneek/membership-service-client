<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use GuzzleHttp\Psr7\Request;

class HunetService extends BaseApiService {

    protected $apiToken;

    public function __construct($apiToken) {
        parent::__construct();

        $this->domain = 'https://xapi.hunet.name';
        $this->apiUrl = "$this->domain/" . 'api/savePublyData';
        $this->apiToken = $apiToken;
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

        $request = $this->attachHeader('');

        while ($retryCount > 0) {
            try {
                $result = $this->guzzle->send($request, ['query' => $queryParams]);
                return $result;
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

        $request = $this->attachHeader('');
        while ($retryCount > 0) {
            try {
                $result = $this->guzzle->send($request, ['query' => $queryParams]);
                return $result;
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

        $request = $this->attachHeader('');

        while ($retryCount > 0) {
            try {
                $result = $this->guzzle->send($request, ['query' => $queryParams]);
                return $result;
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

        $request = $this->attachHeader('');
        while ($retryCount > 0) {
            try {
                $result = $this->guzzle->send($request, ['query' => $queryParams]);
                return $result;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    private function attachHeader($endPoint)
    {
        $headers =
            [
                'Accept' => 'application/json',
                'Authorization' => 'bearer '. $this->apiToken
            ];

        $request = new Request(
            'GET',
            $this->getApiUrl() . $endPoint,
            $headers
        );

        return $request;
    }
}