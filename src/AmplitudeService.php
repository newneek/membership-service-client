<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

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
        return $this->get("identify",
            [
                'api_key' => $this->apiKey,
                'identification' => json_encode(
                    [
                        'user_id' => $userId,
                        'user_properties' => $userProperties
                    ])
            ]);
    }
}