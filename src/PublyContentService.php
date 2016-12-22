<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class PublyContentService extends BaseApiService
{
    const SET_READER_SOURCE_TYPE_ADMIN = 1;
    const SET_READER_SOURCE_TYPE_ORDER = 2;
    
    const HOME_DISPLAY_TYPE_PROJECT = 1;

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

    public function updateContent($contentId, $isPaid)
    {
        return $this->put("content/{$contentId}", [ 'is_paid' => $isPaid ]);
    }

    public function updateContentSet($contentId, $setId, $orderInSet)
    {
        return $this->put("content/{$contentId}/set", [ 'set_id' => $setId,
                                                        'order_in_set' => $orderInSet ]);
    }

    public function updateContentProjectId($contentId, $projectId)
    {
        return $this->put("content/{$contentId}/project", [ 'project_id' => $projectId ]);
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

    public function getContentsByProject($projectId, $filterArray)
    {
        return $this->get("content/project/{$projectId}", $filterArray);
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

    public function getProjectsByIds($projectIds)
    {
        return $this->get("project/by_ids", [ 'ids' => implode(',', $projectIds) ]);
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

    public function updateProjectCoverImage($projectId, $imageUrl)
    {
        return $this->put("project/{$projectId}/image", ['cover_image' => $imageUrl]);
    }

    public function updateProjectListImage($projectId, $imageUrl)
    {
        return $this->put("project/{$projectId}/image", ['list_image' => $imageUrl]);
    }

    public function updateProjectMobileImage($projectId, $imageUrl)
    {
        return $this->put("project/{$projectId}/image", ['mobile_image' => $imageUrl]);
    }

    /*
     * Project Progress Related Functions
     */
    public function getProjectProgressByProject($projectId)
    {
        return $this->get("project_progress/project/{$projectId}");
    }
    
    public function updateAllProjectProgress($includeFinished = false)
    {
        return $this->put("project_progress", [ 'include_finished' => $includeFinished ? 1 : 0 ]);
    }

    public function updateProjectProgress($projectId)
    {
        return $this->put("project_progress/project/{$projectId}");
    }

    public function getProjectProgressesByProjectIds($projectIds, $filterArray = [])
    {
        $filterArray['project_ids'] = implode(',', $projectIds);
        return $this->get("project_progress/by_project_ids", $filterArray);
    }

    /*
     * User Content Progress Related Functions
     */
    public function createUserContentProgress($userId, $contentId)
    {
        return $this->post("user_content_progress", [ 'user_id' => $userId,
                                                      'content_id' => $contentId ]);
    }

    public function updateUserContentProgress($userId, $contentId)
    {
        return $this->put("user_content_progress/user/{$userId}/content/{$contentId}");
    }

    public function getUserContentProgressesByUserAndContentIds($userId, $contentIds)
    {
        $filterArray = [];
        $filterArray['content_ids'] = implode(',', $contentIds);
        return $this->get("user_content_progress/user/{$userId}/by_content_ids", $filterArray);
    }

    public function getUserContentProgress($userId, $contentId)
    {
        return $this->get("user_content_progress/user/{$userId}/content/{$contentId}");
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

    public function getSetsByIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("set/by_ids", $filterArray);
    }

    public function getSetsByProject($projectId)
    {
        return $this->get("set/project/{$projectId}");
    }

    public function getSet($setId, $filterArray = [])
    {
        return $this->get("set/{$setId}", $filterArray);
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
    
    public function loadSetDataFromProject($changerId, $setId) 
    { 
        return $this->post("set/{$setId}/load_data_from_project", [ 'changer_id' => $changerId ]); 
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

    public function getSetReadersBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("set_reader/set_ids", $filterArray);
    }


    public function createSetReader($changerId, $userId, $setId, $sourceType, $adminId, $orderId, $note)
    {
        try {
            return $this->post("set_reader", [  'changer_id' => $changerId,
                                                'user_id' => $userId,
                                                'set_id' => $setId,
                                                'source_type' => $sourceType,
                                                'admin_id' => $adminId,
                                                'order_id' => $orderId,
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

    public function togglePreorder($projectId)
    {
        return $this->post("project/{$projectId}/toggle_preorder");
    }

    public function getHomeDisplay($type, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['type'] = $type;
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("home_display", $filterArray);
    }

    public function createHomeDisplay($changerId, $type, $projectId, $startAt, $finishAt)
    {
        return $this->post("home_display", ['changer_id' => $changerId,
                                           'type' => $type, 
                                           'project_id' => $projectId, 
                                           'start_at' => $startAt,
                                           'finish_at' => $finishAt
                                           ]);
    }

    public function updateHomeDisplayTime($changerId, $homeDisplayId, $startAt, $finishAt)
    {
        return $this->put("home_display/{$homeDisplayId}", ['changer_id' => $changerId,
                                                            'start_at' => $startAt,
                                                            'finish_at' => $finishAt
                                                            ]);
    }

    public function updateHomeDisplayOrder($changerId, $homeDisplayIds)
    {
        return $this->put("home_display/order", [ 'changer_id' => $changerId,
                                                  'ids' => implode(',', $homeDisplayIds) ]);
    }

    public function deleteHomeDisplay($changerId, $homeDisplayId)
    {
        return $this->post("home_display/delete/", [ 'changer_id' => $changerId,
                                                     'homeDisplayId' => $homeDisplayId ]);
    }

    public function getReplies($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("reply", $filterArray);
    }

    public function getChildRepliesByIds($replyIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $replyIds);
        return $this->get("reply/by_parent_ids", $filterArray);
    }

    public function createReply($changerId, $userId, $content, $projectId, $contentId, $parentId)
    {
        return $this->post("reply", ['changer_id' => $changerId, 
                                     'user_id' => $userId, 
                                     'content' => $content, 
                                     'project_id' => $projectId, 
                                     'content_id' => $contentId, 
                                     'parent_id' => $parentId
                                     ]);
    }

    public function deleteReply($changerId, $replyId, $force = false)
    {
        return $this->post("reply/delete/", [ 'changer_id' => $changerId,
                                              'reply_id' => $replyId,
                                              'force' => $force ? 1 : 0 ]);
    }

    public function updateReply($changerId, $replyId, $content, $force = false)
    {
        return $this->put("reply/{$replyId}/", [ 'changer_id' => $changerId,
                                                 'content' => $content,
                                                 'force' => $force ? 1 : 0 ]);
    }

    public function addProjectAuthor($changerId, $projectId, $userId, $isHidden)
    {
        return $this->post("writer", [  'changer_id' => $changerId,
                                        'user_id' => $userId,
                                        'project_id' => $projectId, 
                                        'is_hidden' => $isHidden ]);
    }

    public function removeProjectAuthor($changerId, $projectId, $userId)
    {
        return $this->post("writer/delete/", [ 'changer_id' => $changerId,
                                               'user_id' => $userId,
                                               'project_id' => $projectId ]);
    }

    public function addContentAuthor($changerId, $contentId, $userId, $isHidden)
    {
        return $this->post("writer", [  'changer_id' => $changerId,
                                        'user_id' => $userId,
                                        'content_id' => $contentId, 
                                        'is_hidden' => $isHidden ]);
    }

    public function removeContentAuthor($changerId, $contentId, $userId)
    {
        return $this->post("writer/delete/", [ 'changer_id' => $changerId,
                                               'user_id' => $userId,
                                               'content_id' => $contentId ]);
    }

    public function addSetAuthor($changerId, $setId, $userId, $isHidden)
    {
        return $this->post("writer", [  'changer_id' => $changerId,
                                        'user_id' => $userId,
                                        'set_id' => $setId, 
                                        'is_hidden' => $isHidden ]);
    }

    public function removeSetAuthor($changerId, $setId, $userId)
    {
        return $this->post("writer/delete/", [ 'changer_id' => $changerId,
                                               'user_id' => $userId,
                                               'set_id' => $setId ]);
    }

    public function getProjectWriters($projectId, $filterArray = [])
    {
        return $this->get("writer/project/{$projectId}", $filterArray);
    }

    public function getContentWriters($contentId, $filterArray = [])
    {
        return $this->get("writer/content/{$contentId}", $filterArray);
    }

    public function getWritersByContentIds($contentIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $contentIds);
        return $this->get("writer/content/ids", $filterArray);
    }
}
