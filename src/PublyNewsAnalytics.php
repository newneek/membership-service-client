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
//        $eventBody =  [
//            'event' => [
//                'key' => 'eventKey',
//                'properties' => ['foo' => 'bar'],
//                'eventAt' => '2010-01-01 00:00:00'
//            ],
//            'user' => [
//                'userId' => 2707
//            ],
//            'device' => [
//                'deviceOS' => 'ios',
//                'appVersion' => ''
//            ]
//        ];

        return $this->post("/events", $eventBody);
    }

}