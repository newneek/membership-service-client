<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class PublyContentService extends BaseApiService
{
    const SET_READER_SOURCE_TYPE_ADMIN = 1;
    const SET_READER_SOURCE_TYPE_ORDER = 2;
    
    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    /*
     * Reward Related Functions
     */

    public function getReward($rewardId)
    {
        return $this->get("reward/{$rewardId}");
    }

    public function getRewardsByIds($rewardIds, $includeHidden = false)
    {
        if ($includeHidden) {
            return $this->get("reward/by_ids", [ 'ids' => implode(',', $rewardIds) ]);
        } else {
            return $this->get("reward/by_ids", [ 'ids' => implode(',', $rewardIds),
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

    /*
     * Content Related Functions
     */
    public function getContents($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("content", $filterArray);
    }

    public function getContentsBySet($setId, $filterArray)
    {
        return $this->get("content/set/{$setId}", $filterArray);
    }

    public function updateContentSet($contentId, $setId, $orderInSet)
    {
        return $this->put("content/{$contentId}/set", [ 'set_id' => $setId,
                                                        'order_in_set' => $orderInSet ]);
    }

    public function updateContentsOrderInSet($setId, $contentIds)
    {
        return $this->put("content/set/{$setId}", [ 'ids' => implode(',', $contentIds) ]);
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
        } else {
            return $this->get("content/project/{$projectId}", [ 'is_hidden' => false ]);
        }
    }

    /*
     * Project Related Functions
     */
    public function getProject($projectId)
    {
        return $this->get("project/{$projectId}");
    }

    public function getProjects($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("project", $filterArray);
    }

    public function getProjectsBySet($setId, $filterArray = []) 
    { 
        return $this->get("project/set/{$setId}", $filterArray); 
    } 
 
    public function createProjectSet($projectId, $setId) 
    { 
        return $this->post("project/{$projectId}/set/{$setId}"); 
    } 
 
    public function removeProjectSet($projectId, $setId) 
    { 
        return $this->post("project/{$projectId}/set/{$setId}/delete"); 
    } 
 

    /*
     * Project Progress Related Functions
     */
    public function updateAllProjectProgress($includeFinished = false)
    {
        return $this->put("project_progress", [ 'include_finished' => $includeFinished ? 1 : 0 ]);
    }

    public function updateProjectProgress($projectId)
    {
        return $this->put("project_progress/project/{$projectId}");
    }

    /*
     * Set Related Functions
     */
    public function getSets($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("set", $filterArray);
    }

    public function getSetsByProject($projectId)
    {
        return $this->get("set/project/{$projectId}");
    }

    public function getSet($setId)
    {
        return $this->get("set/{$setId}");
    }

    public function createSet($changerId, $title)
    {
        return $this->post("set", [ 'changer_id' => $changerId,
                                    'title' => $title ]);
    }

    public function updateSet($setId, $changerId, $title)
    {
        return $this->put("set/{$setId}", [ 'changer_id' => $changerId,
                                            'title' => $title ]);
    }
    /*
     * SetReader Related Functions
     */
    public function getSetReader($setReaderId)
    {
        return $this->get("set_reader/{$setReaderId}");
    }

    public function getTotalSetReader($userId, $setId)
    {
        return $this->get("set_reader/total", [ 'user_id' => $userId,
                                                'set_id' => $setId ]);
    }

    public function getSetReadersByUserId($userId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        $filterArray['user_id'] = $userId;
        return $this->get("set_reader/", $filterArray);
    }
    
    public function getSetReadersBySetId($setId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        $filterArray['set_id'] = $setId;
        return $this->get("set_reader/", $filterArray);
    }

    public function createSetReader($userId, $setId, $sourceType, $adminId, $orderId, $expireAt, $note)
    {
        try {
            return $this->post("set_reader", [  'user_id' => $userId,
                                                'set_id' => $setId,
                                                'source_type' => $sourceType,
                                                'admin_id' => $adminId,
                                                'order_id' => $orderId,
                                                'expire_at' => $expireAt,
                                                'note' => $note ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function updateSetReader($setReaderId, $changerId, $note)
    {
        return $this->put("set_reader/{$setReaderId}", ['changer_id' => $changerId,
                                                        'note' => $note ]);
    }

    public function deleteSetReader($params)
    {
        return $this->post("set_reader/delete", $params);
    }
}
