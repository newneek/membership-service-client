<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class KlaviyoApiService extends BaseApiService
{
    protected $apiKey;

    public function __construct($domain, $apiKey)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
        $this->apiKey = $apiKey;
    }

    public function subscribeToList($listId, $email)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->post("/api/v2/list/{$listId}/subscribe", [
                    'api_key' => $this->apiKey,
                    'profiles' => [
                        [
                            'email' => $email
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