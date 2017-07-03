<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublySettlementService extends BaseApiService
{

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function createSubscriptionUserContentView($userId,
                                                      $setId,
                                                      $contentId,
                                                      $settlementYear,
                                                      $settlementMonth)
    {
        $inputs = [ 'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth];

        return $this->put("subscription_user_content_view", $inputs);
    }

}