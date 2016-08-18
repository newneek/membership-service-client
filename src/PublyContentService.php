<?php

namespace App\Services;

use App\Services\Api\BaseApiService;

class PublyContentService extends BaseApiService {
    
    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";        
    }

    public function getReward($rewardId)
    {
    	return $this->get("reward/{$rewardId}");
    }

    public function getRewardsByIds($rewardIds)
    {
        return $this->get("reward/by_ids", [ 'ids' => implode(',', $rewardIds) ]);
    }

    public function getRewardsByProject($projectId)
    {
        return $this->get("reward/project/{$projectId}");
    }

    public function getContent($contentId)
    {
    	return $this->get("content/{$contentId}");
    }

    public function getContentsByIds($contentIds)
    {
        return $this->get("content/by_ids", [ 'ids' => implode(',', $contentIds) ]);
    }

    public function getProjects($filterArray = null)
    {
        return $this->get("project", $filterArray);
    }
    
    public function getContents($filterArray = null)
    {
        return $this->get("content", $filterArray);
    }

    public function getProject($projectId)
    {
        return $this->get("project/{$projectId}");
    }
    
    
}