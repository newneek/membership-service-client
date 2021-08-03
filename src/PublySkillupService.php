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

    public function getVoucherByUser($userId, $filter = [])
    {
        $filter['userId'] = $userId;
        return $this->get("coupon/voucher", $filter);
    }

    public function createVoucher($userId, $email, $userName, $campaignId)
    {
        return $this->post("coupon/voucher", [
            'userId' => $userId,
            'email' => $email,
            'userName' => $userName,
            'campaignName' => $campaignId
        ]);
    }
}
