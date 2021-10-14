<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class PublyNewsService extends BaseApiService
{
    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/api/";
    }

    public function createPost(int $userId, string $description, string $originUrl)
    {
        return $this->postWithHeader("posts", [
            'description' => $description,
            'originUrl' => $originUrl
        ],
            [
                'x-user-id' => $userId
            ]
        );
    }

}
