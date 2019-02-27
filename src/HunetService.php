<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class HunetService extends BaseApiService {

    public function __construct() {
        parent::__construct();

        $this->domain = 'https://sample.com';
        $this->apiUrl = "$this->domain/";

        $this->timeout = 1;
        $this->maxRetryCount = 10;
    }

    public function subscriptionUserContentView()
    {
        
    }

    public function completeUserContentProgress()
    {

    }

    public function setReview()
    {

    }

    public function setLike()
    {

    }
}