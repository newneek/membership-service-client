<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class AmplitudeService extends BaseApiService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        parent::__construct();

        $this->domain = 'https://api2.amplitude.com';
        $this->apiUrl = "$this->domain/";
        $this->apiKey = $apiKey;
    }

    public function identify($userId, $userProperties)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                // https://developers.amplitude.com/docs/identify-api
                // POST로 바꾸고 싶었으나, 쿼리 파라미터로 보내는게 아니면 동작 안해서, 그냥 GET으로 보냄
                $result = $this->get("identify", [
                    'api_key' => $this->apiKey,
                    'identification' => json_encode([
                        'user_id' => $userId,
                        'user_properties' => $userProperties
                    ])
                ]);
                return $result;
            } catch (ResponseException $e) {
                // for any response exception, retry
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function event($userId, $eventType, $eventProperties, $userProperties)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->post("2/httpapi", [
                    'api_key' => $this->apiKey,
                    'events' => [
                        [
                            'user_id' => $userId,
                            'event_type' => $eventType,
                            'event_properties' => $eventProperties,
                            'user_properties' => $userProperties
                        ]
                    ]
                ]);
                return $result;
            } catch (ResponseException $e) {
                // for any response exception, retry
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function sendBatchEvents($events)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->post("batch",
                    [
                        'api_key' => $this->apiKey,
                        'events' => $events
                    ]);
                return $result;
            } catch (ResponseException $e) {
                // for any response exception, retry
                if ($e->getCode() === 429) {
                    // rate limit exceeded
                    throw $e;
                }
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function revenue($userId, $revenueType, $eventType, $price, $productId, $insertId, $time, $eventProperties = null)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->post("2/httpapi", [
                    'api_key' => $this->apiKey,
                    'events' => [
                        [
                            'user_id' => $userId,
                            'price' => $price,
                            'productId' => $productId,
                            'revenueType' => $revenueType,
                            'event_type' => $eventType,
                            'insert_id' => $insertId,
                            'time' => $time,
                            'event_properties' => $eventProperties
                        ]
                    ]
                ]);
                return $result;
            } catch (ResponseException $e) {
                // for any response exception, retry
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }
}