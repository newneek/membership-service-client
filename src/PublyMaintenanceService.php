<?php

namespace Publy\ServiceClient;

use App\Services\Api\BaseApiService;

class PublyMaintenanceService extends BaseApiService {

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";        
    }     
}