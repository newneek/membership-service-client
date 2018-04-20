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

        $this->domain = 'https://api.amplitude.com';
        $this->apiUrl = "$this->domain/";
        $this->apiKey = $apiKey;
    }

    public function identify($userId, $userProperties)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->get("identify",
                    [
                        'api_key' => $this->apiKey,
                        'identification' => json_encode(
                            [
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
                $result = $this->get("httpapi",
                    [
                        'api_key' => $this->apiKey,
                        'event' => json_encode(
                            [
                                'user_id' => $userId,
                                'event_type' => $eventType,
                                'event_properties' => $eventProperties,
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

    public function revenue($userId, $revenueType, $eventType, $price, $productId, $insertId, $time, $eventProperties = null)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->get("httpapi",
                    [
                        'api_key' => $this->apiKey,
                        'event' => json_encode(
                            [
                                'user_id' => $userId,
                                'price' => $price,
                                'productId' => $productId,
                                'revenueType' => $revenueType,
                                'event_type' => $eventType,
                                'insert_id' => $insertId,
                                'time' => $time,
                                'event_properties' => $eventProperties
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
}