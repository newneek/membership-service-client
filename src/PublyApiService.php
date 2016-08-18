<?php

namespace Publy\ServiceClient;

use App\Services\Api\BaseApiService;

class PublyApiService extends BaseApiService {

    public function __construct() {
        parent::__construct();

        $this->domain = config('services.publy_api.domain');
        $this->apiUrl = "$this->domain/";        
    }    
}