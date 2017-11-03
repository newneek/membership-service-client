<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublySettlementService extends BaseApiService
{
    const TAX_PAYER_TYPE_DIRECTION_AUTHOR_TO_COMPANY = 1;
    const TAX_PAYER_TYPE_DIRECTION_COMPANY_TO_AUTHOR = 2;    

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

    public function createSubscriptionUserContentView2($userId,
                                                       $setId,
                                                       $contentId,
                                                       $subscriptionId,
                                                       $couponUseHistoryId,
                                                       $settlementYear,
                                                       $settlementMonth)
    {
        $inputs = [ 'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId,
            'subscription_id' => $subscriptionId,
            'coupon_use_history_id' => $couponUseHistoryId,
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
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("settlement_author_result", $filterArray);
    }

    public function getSettlementAuthorResultsByAuthor($authorId, $filterArray = [])
    {
        return $this->get("settlement_author_result/author/{$authorId}", $filterArray);
    }

    public function getSettlementAuthorResultsTotalPriceGroupByAuthor($filterArray = [])
    {
        return $this->get("settlement_author_result/total_price_group_by_author", $filterArray);
    }

    public function getSettlementAuthorResult($settlementYear,
                                              $settlementMonth,
                                              $authorId)
    {
        return $this->get("settlement_author_result/{$settlementYear}/{$settlementMonth}/{$authorId}");
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

    public function getTaxPayerTypes()
    {
        return $this->get("tax_payer_type");
    }

    public function addTaxPayerType($changerId, $name, $taxName, $ratePermille, $direction)
    {
        return $this->post("tax_payer_type", [  'changer_id' => $changerId,
                                                'name' => $name,
                                                'tax_name' => $taxName,
                                                'rate_permille' => $ratePermille,
                                                'direction' => $direction ]);
    }

    public function deleteTaxPayerType($changerId, $taxPayerTypeId)
    {
        return $this->post("tax_payer_type/delete", [ 'changer_id' => $changerId,
                                                      'tax_payer_type_id' => $taxPayerTypeId ]);
    }

    public function getAuthorSettlementProfiles()
    {
        return $this->get("author_settlement_profile");
    }

    public function getAuthorSettlementProfile($authorId)
    {
        return $this->get("author_settlement_profile/{$authorId}");
    }

    public function updateOrCreateAuthorSettlementProfile($changerId, $authorId, $taxPayerTypeId)
    {
        return $this->put("author_settlement_profile/{$authorId}", [ 'changer_id' => $changerId,
                                                                     'tax_payer_type_id' => $taxPayerTypeId ]);
    }
}