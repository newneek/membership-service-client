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

    public function addAuthorRate($changerId, $setId, $userId, $rate)
    {
        return $this->post("author_rate", [  'changer_id' => $changerId,
            'author_id' => $userId,
            'set_id' => $setId,
            'rate' => $rate ]);
    }

    public function removeAuthorRate($changerId, $authorRateId)
    {
        return $this->post("author_rate/delete", [ 'changer_id' => $changerId,
            'author_rate_id' => $authorRateId ]);
    }

    public function getAuthorRatesBySetId($setId, $filterArray = [])
    {
        $filterArray['set_id'] = $setId;
        return $this->get("author_rate", $filterArray);
    }

    public function getSettlementResults($filterArray = [])
    {
        return $this->get("settlement_result", $filterArray);
    }

    public function getSettlementAuthorResults($page = 1,
                                               $limit = 10,
                                               $filterArray = [])
    {
        return $this->get("settlement_author_result", $filterArray);
    }

    public function getSettlementDetails($page = 1,
                                         $limit = 10,
                                         $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("settlement_detail", $filterArray);
    }

    public function calculate($changerId,
                              $settlementYear,
                              $settlementMonth)
    {
        return $this->post("settlement_result/calculate", ['changer_id' => $changerId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth ]);
    }

    public function finishSettlementAuthorResult($changerId,
                                                 $settlementAuthorResultId)
    {
        return $this->post("settlement_author_result/{$settlementAuthorResultId}/finish",
            ['changer_id' => $changerId]);
    }
}