<?php

namespace Publy\ServiceClient;

use Api\BaseApiService;

class PublyApiService extends BaseApiService {

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";        
    }     
}