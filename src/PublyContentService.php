<?php

namespace Publy\ServiceClient;

use Api\BaseApiService;

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

    public function getRewardsByIds($rewardIds, $includeHidden = false)
    {
        if ($includeHidden) {
            return $this->get("reward/by_ids", [ 'ids' => implode(',', $rewardIds) ]);
        } else {
            return $this->get("reward/by_ids", [ 'ids' => implode(',', $rewardIds) ,
                                                 'is_hidden' => 0 ]);
        }
    }

    public function getRewardsByProject($projectId, $includeHidden = false)
    {
        if ($includeHidden) {            
            return $this->get("reward/project/{$projectId}");
        } else {
            return $this->get("reward/project/{$projectId}", [ 'is_hidden' => 0 ]);
        }
    }

    public function getContent($contentId)
    {
    	return $this->get("content/{$contentId}");
    }

    public function getContentsByIds($contentIds)
    {
        return $this->get("content/by_ids", [ 'ids' => implode(',', $contentIds) ]);
    }

    public function getContentsByProject($projectId, $includeHidden)
    {
        if ($includeHidden) {
            return $this->get("content/project/{$projectId}");
        }
        else {
            return $this->get("content/project/{$projectId}", [ 'is_hidden' => false ]);
        }        
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