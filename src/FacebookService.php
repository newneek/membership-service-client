<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class FacebookService extends BaseApiService {

    public function __construct() {
        parent::__construct();

        $this->domain = 'https://graph.facebook.com';
        $this->apiUrl = "$this->domain/";

        $this->timeout = 1;
        $this->maxRetryCount = 10;
    }

    public function getUserInfo($accessToken, $fields) {
    	return $this->get('me', array('fields' => $fields,
                                      'access_token' => $accessToken));
    }
}