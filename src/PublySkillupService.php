<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublySkillupService extends BaseApiService
{

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/api/";
    }

    public function createUserStatus($userId): array
    {
        return $this->post("user-status", [
            'userId' => $userId
        ]);
    }

    public function addProjectLike($userId, $projectId): array
    {
        return $this->post("like/project", [
            'userId' => $userId,
            'likeableId' => $projectId
        ]);
    }

    public function removeProjectLike($userId, $projectId): array
    {
        return $this->post("like/delete/project", [
            'likeableId' => $projectId,
            'userId' => $userId
        ]);
    }

    public function getProjectLikesByUser($userId): array
    {
        return $this->get("like/project", [
            'userId' => $userId
        ]);
    }

    public function getProjectLikeByProjectIdAndUserId($userId, $projectId): array
    {
        return $this->get("like/project/{$projectId}/user/{$userId}");
    }

    public function getSetReviewsBySetIds($setIds, $filter = []): array
    {
        $filter['reviewableIds'] = implode(',', $setIds);

        return $this->get("review/set", $filter);
    }

    public function getSetReviewsWithReactionCount($page, $limit, $setId, $filter = []): array
    {
        return $this->get("review/set/with-reaction-count", [
            'reviewableId' => $setId,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    public function addReviewReaction($userId, $reviewId): array
    {
        return $this->post("reaction/review", [
            'reactableId' => $reviewId,
            'userId' => $userId
        ]);
    }

    public function removeReviewReaction($userId, $reviewId): array
    {
        return $this->post("reaction/delete/review", [
            'reactableId' => $reviewId,
            'userId' => $userId
        ]);
    }

    public function updateOrCreateSetReview($userId, $setId, $rating, $comment = null): array
    {
        return $this->post("review/set", [
            'userId' => $userId,
            'reviewableId' => $setId,
            'rating' => $rating,
            'comment' => $comment
        ]);
    }

    public function updateSetReviewIsRecommended($userId, $setId, $isRecommended): array
    {
        return $this->post("review/set", [
            'userId' => $userId,
            'reviewableId' => $setId,
            'isRecommended' => $isRecommended
        ]);
    }

    public function getCouponCampaign($campaignId): array
    {
        return $this->get("coupon/campaign/$campaignId");
    }

    public function getCouponCampaigns($page = 1, $limit = 10, $filterArray = []): array
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("coupon/campaign", $filterArray);
    }

    public function getVoucher($code)
    {
        return $this->get("coupon/voucher/$code");
    }

    public function getVoucherByUser($userId, $filter = [])
    {
        $filter['userId'] = $userId;
        return $this->get("coupon/voucher", $filter);
    }

    public function createVoucher($userId, $email, $userName, $campaignName)
    {
        return $this->post("coupon/voucher", [
            'userId' => $userId,
            'email' => $email,
            'userName' => $userName,
            'campaignName' => $campaignName
        ]);
    }

    public function validateVoucher($voucherCode, $price, $productName, $userId, $email, $userName)
    {
        return $this->post("coupon/voucher/validate", [
            'voucherCode' => $voucherCode,
            'price' => $price,
            'productName' => $productName,
            'userId' => $userId,
            'email' => $email,
            'userName' => $userName
        ]);
    }


    public function redeemVoucher($orderId, $voucherCode, $price, $productName, $userId, $email, $userName, $projectId)
    {
        return $this->post("coupon/voucher/redeem", [
            'orderId' => $orderId,
            'voucherCode' => $voucherCode,
            'price' => $price,
            'productName' => $productName,
            'userId' => $userId,
            'email' => $email,
            'userName' => $userName,
            'projectId' => $projectId
        ]);
    }

    public function notifyVoucherExpired()
    {
        return $this->post("coupon/voucher/notify-expired");
    }

    public function createFreeContent($contentId)
    {
        return $this->post("free-content", [
            'contentId' => $contentId
        ]);
    }

    public function deleteFreeContent($freeContentId)
    {
        return $this->delete("free-content/{$freeContentId}");
    }

    public function updateFreeContentOrder($contentIds)
    {
        $contentIds = implode(',', $contentIds);

        return $this->put("free-content/update-order", [
            'contentIds' => $contentIds
        ]);
    }

    public function getFreeContents($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("free-content", $filterArray);
    }
}
