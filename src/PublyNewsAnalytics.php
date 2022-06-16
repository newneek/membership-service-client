<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class PublyNewsAnalyticsService extends BaseApiService
{
    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function sendEvent($eventBody)
    {
        return $this->post("events", $eventBody);
    }

}
