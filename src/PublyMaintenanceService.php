<?php

namespace Publy\ServiceClient;

use Api\BaseApiService;
use Api\ResponseException;

class PublyMaintenanceService extends BaseApiService {

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";        
    }     

    public function getMaintenance()
    {
    	return $this->get('maintenance');
    }

    public function getExceptionIps()
    {
    	return $this->get('exception_ip');
    }
}