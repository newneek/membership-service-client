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
    public function oauthKakaoCallbackForWeb($code, $redirectUri)
    {
        return $this->post("auth/web/oauth/kakao/callback", [
            'code' => $code,
            'redirect_uri' => $redirectUri
        ]);
    }

    public function oauthAppleCallbackForWeb($params)
    {
        return $this->post("auth/web/oauth/apple/callback", $params);
    }

    public function getKakaoUserWithToken($token)
    {
        return $this->post("auth/kakao/oauth");
    }

    public function getAppleUserWithAppleUser($appleUser)
    {
        return $this->post("auth/apple/oauth", array('appleUser' => $appleUser));
    }

    public function retrieveUserByAppleIdentityToken($params)
    {
        return $this->post("auth/apple/oauth/callback", $params);
    }
}
