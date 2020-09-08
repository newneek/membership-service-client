<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublySettlementService extends BaseApiService
{
    const TAX_PAYER_TYPE_DIRECTION_AUTHOR_TO_COMPANY = 1;
    const TAX_PAYER_TYPE_DIRECTION_COMPANY_TO_AUTHOR = 2;

    const SETTLEMENT_RESULT_STATUS_CALCULATED = 1;
    const SETTLEMENT_RESULT_STATUS_CALCULATING = 2;
    const SETTLEMENT_RESULT_STATUS_FAIL_TO_CALCULATE = 3;
    const SETTLEMENT_RESULT_STATUS_CONFIRMED = 4;

    const AUTHOR_SETTLEMENT_TRANSFER_STATUS_COMPLETED = 1;
    const AUTHOR_SETTLEMENT_TRANSFER_STATUS_REQUESTED = 2;
    const AUTHOR_SETTLEMENT_TRANSFER_STATUS_REJECTED = 3;

    const SETTLEMENT_TYPE_MEMBERSHIP = 1;
    const SETTLEMENT_TYPE_PROJECT = 2;

    const STRING_SETTLEMENT_RESULT_STATUS = [
        PublySettlementService::SETTLEMENT_RESULT_STATUS_CALCULATED => "정산 완료",
        PublySettlementService::SETTLEMENT_RESULT_STATUS_CALCULATING => "정산중",
        PublySettlementService::SETTLEMENT_RESULT_STATUS_FAIL_TO_CALCULATE => "정산 실패(취소)",
        PublySettlementService::SETTLEMENT_RESULT_STATUS_CONFIRMED => "확인 완료"
    ];
    
    const STRING_AUTHOR_SETTLEMENT_TRANSFER_STATUS = [
        PublySettlementService::AUTHOR_SETTLEMENT_TRANSFER_STATUS_COMPLETED => "지급 완료",
        PublySettlementService::AUTHOR_SETTLEMENT_TRANSFER_STATUS_REQUESTED => "지급 신청",
        PublySettlementService::AUTHOR_SETTLEMENT_TRANSFER_STATUS_REJECTED => "지급 반려"
    ];
    
    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function createSubscriptionUserContentView(
        $userId,
        $setId,
        $contentId,
        $couponUseHistoryId,
        $voucherUseHistoryId,
        $settlementYear,
        $settlementMonth,
        $readerId
    ) {
        $inputs = [
            'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId,
            'coupon_use_history_id' => $couponUseHistoryId,
            'voucher_use_history_id' => $voucherUseHistoryId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth,
            'reader_id' => $readerId
        ];

        return $this->put("subscription_user_content_view", $inputs);
    }

    public function createSubscriptionUserContentView4(
        $userId,
        $setId,
        $contentId,
        $couponUseHistoryId,
        $settlementYear,
        $settlementMonth,
        $readerId
    ) {
        $inputs = [
            'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId,
            'coupon_use_history_id' => $couponUseHistoryId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth,
            'reader_id' => $readerId
        ];

        return $this->put("subscription_user_content_view", $inputs);
    }

    public function addAuthorRate($changerId, $setId, $userId, $rate)
    {
        return $this->post("author_rate", [
            'changer_id' => $changerId,
            'author_id' => $userId,
            'set_id' => $setId,
            'rate' => $rate
        ]);
    }

    public function addAuthorRate2($changerId, $setId, $userId, $rate, $type)
    {
        return $this->post("author_rate", [
            'changer_id' => $changerId,
            'author_id' => $userId,
            'set_id' => $setId,
            'rate' => $rate,
            'type' => $type
        ]);
    }

    public function removeAuthorRate($changerId, $authorRateId)
    {
        return $this->post("author_rate/delete", [
            'changer_id' => $changerId,
            'author_rate_id' => $authorRateId
        ]);
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

    public function getSettlementResultByYearAndMonth(
        $settlementYear,
        $settlementMonth,
        $filterArray = []
    ) {
        return $this->get("settlement_result/settlement_year/{$settlementYear}/settlement_month/$settlementMonth", $filterArray);
    }

    public function updateSettlementResult($changerId, $inputs = [])
    {
        $inputs = array_merge($inputs, ["changer_id" => $changerId]);
        return $this->put("settlement_result", $inputs);
    }

    public function confirmSettlementAuthorResult($changerId, $authorId, $settlementYear, $settlementMonth)
    {
        return $this->post("settlement_author_result/confirm", [
            'changer_id' => $changerId,
            'author_id' => $authorId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth
        ]);
    }

    public function getSettlementAuthorResults(
        $page = 1,
        $limit = 10,
        $filterArray = []
    ) {
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

    public function getSettlementAuthorResult(
        $settlementYear,
        $settlementMonth,
        $authorId
    ) {
        return $this->get("settlement_author_result/{$settlementYear}/{$settlementMonth}/{$authorId}");
    }

    public function getSettlementDetails(
        $page = 1,
        $limit = 10,
        $filterArray = []
    ) {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("settlement_detail", $filterArray);
    }

    public function calculate(
        $changerId,
        $settlementYear,
        $settlementMonth
    ) {
        return $this->post("settlement_result/calculate", [
            'changer_id' => $changerId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth
        ]);
    }

    public function getTaxPayerTypes()
    {
        return $this->get("tax_payer_type");
    }

    public function getTaxPayerType($taxPayerTypeId)
    {
        return $this->get("tax_payer_type/{$taxPayerTypeId}");
    }

    public function addTaxPayerType($changerId, $name, $taxName, $ratePermille, $direction)
    {
        return $this->post("tax_payer_type", [
            'changer_id' => $changerId,
            'name' => $name,
            'tax_name' => $taxName,
            'rate_permille' => $ratePermille,
            'direction' => $direction
        ]);
    }

    public function deleteTaxPayerType($changerId, $taxPayerTypeId)
    {
        return $this->post("tax_payer_type/delete", [
            'changer_id' => $changerId,
            'tax_payer_type_id' => $taxPayerTypeId
        ]);
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
        return $this->put("author_settlement_profile/{$authorId}", [
            'changer_id' => $changerId,
            'tax_payer_type_id' => $taxPayerTypeId
        ]);
    }

    public function calculateSettlementAuthorResult($changerId, $authorId, $settlementYear, $settlementMonth)
    {
        return $this->post("settlement_author_result/calculate", [
            'changer_id' => $changerId,
            'author_id' => $authorId,
            'settlement_year' => $settlementYear,
            'settlement_month' => $settlementMonth
        ]);
    }

    public function getSetUniqueReaderCount($filterArray = [])
    {
        return $this->get("subscription_user_content_view/count", $filterArray);
    }

    public function getUniqueReaderCountBySet($setId, $filterArray = [])
    {
        return $this->get("subscription_user_content_view/set/{$setId}/count", $filterArray);
    }

    // deprecated
    public function createAuthorSettlementTransfer(
        $changerId,
        $authorId,
        $priceBeforeTax,
        $note,
        $force = false
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'author_id' => $authorId,
            'price_before_tax' => $priceBeforeTax,
            'note' => $note,
            'force' => $force
        ];

        return $this->post("author_settlement_transfer", $inputs);
    }

    public function createAuthorSettlementTransfer2(
        $changerId,
        $authorId,
        $priceBeforeTax,
        $note,
        $requestedAt,
        $force = false
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'author_id' => $authorId,
            'price_before_tax' => $priceBeforeTax,
            'note' => $note,
            'requested_at' => $requestedAt,
            'force' => $force
        ];

        return $this->post("author_settlement_transfer", $inputs);
    }

    public function getAuthorSettlementTransfers(
        $page = 1,
        $limit = 10,
        $filterArray = []
    )
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("author_settlement_transfer", $filterArray);
    }

    public function getAuthorSettlementTransfersByAuthor($authorId, $filterArray = [])
    {
        return $this->get("author_settlement_transfer/author/{$authorId}", $filterArray);
    }

    public function getAuthorSettlementTransferTotalPriceBeforeTaxGroupByAuthor($filterArray = [])
    {
        return $this->get("author_settlement_transfer/total_price_before_tax_group_by_author", $filterArray);
    }

    public function modifyAuthorSettlementTransfer($changerId, $authorSettlementTransferId)
    {
        return $this->put("author_settlement_transfer/{$authorSettlementTransferId}", [
            'changer_id' => $changerId,
            'action' => 'modify'
        ]);
    }

    public function completeAuthorSettlementTransfer($changerId, $authorSettlementTransferId, $tax, $transferredAt = null)
    {
        return $this->put("author_settlement_transfer/{$authorSettlementTransferId}", [
            'changer_id' => $changerId,
            'action' => 'complete',
            'tax' => $tax,
            'transferred_at' => $transferredAt
        ]);
    }

    public function rejectAuthorSettlementTransfer($changerId, $authorSettlementTransferId)
    {
        return $this->put("author_settlement_transfer/{$authorSettlementTransferId}", [
            'changer_id' => $changerId,
            'action' => 'reject'
        ]);
    }

    public function deleteAuthorSettlementTransfer($changerId, $authorSettlementTransferId)
    {
        return $this->post("author_settlement_transfer/{$authorSettlementTransferId}/delete", [
            'changer_id' => $changerId
        ]);
    }

    public function createOtherAuthorSettlement(
        $changerId,
        $authorId,
        $price,
        $note
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'author_id' => $authorId,
            'price' => $price,
            'note' => $note
        ];

        return $this->post("other_author_settlement", $inputs);
    }

    public function getOtherAuthorSettlements(
        $page = 1,
        $limit = 10,
        $filterArray = []
    )
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("other_author_settlement", $filterArray);
    }

    public function getOtherAuthorSettlementsByAuthor($authorId, $filterArray = [])
    {
        return $this->get("other_author_settlement/author/{$authorId}", $filterArray);
    }

    public function getOtherAuthorSettlementTotalPriceGroupByAuthor($filterArray = [])
    {
        return $this->get("other_author_settlement/total_price_group_by_author", $filterArray);
    }

    public function updateOtherAuthorSettlement($changerId, $otherAuthorSettlementId)
    {
        return $this->put("other_author_settlement/{$otherAuthorSettlementId}/update", [
            'changer_id' => $changerId,
        ]);
    }

    public function deleteOtherAuthorSettlement($changerId, $otherAuthorSettlementId)
    {
        return $this->post("other_author_settlement/{$otherAuthorSettlementId}/delete", [
            'changer_id' => $changerId
        ]);
    }

    public function getSettlementAuthorSetResultsByAuthor($authorId, $filterArray = [])
    {
        return $this->get("settlement_author_set_result/author/{$authorId}", $filterArray);
    }

    public function getSettlementAuthorSetResultByYearAndMonthAndAuthorAndSet($settlementYear, $settlementMonth, $authorId, $setId)
    {
        return $this->get("/settlement_author_set_result/settlement_year/{$settlementYear}/settlement_month/{$settlementMonth}/author/{$authorId}/set/{$setId}");
    }

    public function getSettlementSetUserDetailsBySet($setId, $page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("settlement_set_user_detail/set/{$setId}", $filterArray);
    }

    public function getSettlementSetUserDetailTotalUserViewCounts($userIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $userIds);
        return $this->get("settlement_set_user_detail/user_total_view_count_by_users", $filterArray);
    }

    public function getSettlementSetUserDetailsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("settlement_set_user_detail/by_set_ids", $filterArray);
    }

    public function getProjectSettlementResults($filterArray = [])
    {
        return $this->get("project_settlement_result", $filterArray);
    }
}