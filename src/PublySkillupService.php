<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublySkillupService extends BaseApiService {

    public function __construct($domain) {
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

    public function getSetReviewsBySetIds($setIds): array
    {
        return $this->get("review/set", [
            'reviewableIds' => implode(',', $setIds)
        ]);
    }
}
