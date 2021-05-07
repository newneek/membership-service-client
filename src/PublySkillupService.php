<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublySkillupService extends BaseApiService {

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/api/";
    }

    public function createUserStatus($userId): array
    {
        return $this->post("user-status", [
            'userId' => $userId
        ]);
    }
}
