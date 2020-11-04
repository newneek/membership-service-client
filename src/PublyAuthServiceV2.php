<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyAuthServiceV2 extends BaseApiService {


    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/api/";
    }

    /*
     * User Related Interfaces
     */
    public function getKakaoUserWithToken($token)
    {
        return $this->post("auth/kakao/oauth", array('token' => $token));
    }

    public function getAppleUserWithAppleUser($appleUser)
    {
        return $this->post("auth/apple/oauth", array('appleUser' => $appleUser));
    }
}
