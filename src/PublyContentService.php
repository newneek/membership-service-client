<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class PublyContentService extends BaseApiService
{
    const SET_REVIEW_IS_HIDDEN_FALSE = 0;
    const SET_REVIEW_IS_HIDDEN_TRUE = 1;

    const CURATION_TYPE_LIST = 1;
    const CURATION_TYPE_CAROUSEL = 2;
    const CURATION_TYPE_RANK_UNIQUE_SET_READER = 3;

    const STRING_CURATION_TYPE = [
        PublyContentService::CURATION_TYPE_RANK_UNIQUE_SET_READER => '최근 인기 콘텐츠',
    ];

    const SET_READER_SOURCE_TYPE_ADMIN = 1;
    const SET_READER_SOURCE_TYPE_ORDER = 2;

    const PACKAGE_READER_SOURCE_TYPE_ADMIN = 1;
    const PACKAGE_READER_SOURCE_TYPE_SUBSCRIPTION = 2;
    const PACKAGE_READER_SOURCE_TYPE_COUPON = 3;
    const PACKAGE_READER_SOURCE_TYPE_VOUCHER = 4;

    const HOME_DISPLAY_TYPE_PROJECT = 1;

    const PROJECT_STATUS_UNDER_CONSIDERATION = 1;
    const PROJECT_STATUS_PREORDER = 2;
    const PROJECT_STATUS_PAYMENT_IN_PROGRESS = 3;
    const PROJECT_STATUS_PREORDER_DONE = 4;
    const PROJECT_STATUS_SALES = 5;
    const PROJECT_STATUS_DROP = 6;

    const STRING_PROJECT_STATUS = [
        PublyContentService::PROJECT_STATUS_UNDER_CONSIDERATION => "검토중",
        PublyContentService::PROJECT_STATUS_PREORDER => "예약구매",
        PublyContentService::PROJECT_STATUS_PAYMENT_IN_PROGRESS => "결제중",
        PublyContentService::PROJECT_STATUS_PREORDER_DONE => "예약구매종료",
        PublyContentService::PROJECT_STATUS_SALES => "즉시구매",
        PublyContentService::PROJECT_STATUS_DROP => "중단"
    ];

    const USER_CONTENT_PROGRESS_TYPE_INDIVIDUAL = 1;
    const USER_CONTENT_PROGRESS_TYPE_PACKAGE = 2;

    const NO_PAGE_LIMIT = 0;

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    /*
     * Reward Related Functions
     */

    public function createReward(
        $changerId,
        $projectId,
        $name,
        $needDelivery,
        $price,
        $quantity,
        $description
    ) {
        return $this->post("reward", [
            'changer_id' => $changerId,
            'project_id' => $projectId,
            'name' => $name,
            'need_delivery' => $needDelivery,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description
        ]);
    }

    public function createReward2(
        $changerId,
        $projectId,
        $name,
        $needDelivery,
        $price,
        $quantity,
        $hasOffline,
        $description
    ) {
        return $this->post("reward", [
            'changer_id' => $changerId,
            'project_id' => $projectId,
            'name' => $name,
            'need_delivery' => $needDelivery,
            'price' => $price,
            'quantity' => $quantity,
            'has_offline' => $hasOffline,
            'description' => $description
        ]);
    }

    public function createReward3(
        $changerId,
        $projectId,
        $name,
        $needDelivery,
        $price,
        $quantity,
        $hasOffline,
        $description,
        $subscriptionName,
        $subscriptionPrice,
        $subscriptionDescription
    ) {
        return $this->post("reward", [
            'changer_id' => $changerId,
            'project_id' => $projectId,
            'name' => $name,
            'need_delivery' => $needDelivery,
            'price' => $price,
            'quantity' => $quantity,
            'has_offline' => $hasOffline,
            'description' => $description,
            'subscription_name' => $subscriptionName,
            'subscription_price' => $subscriptionPrice,
            'subscription_description' => $subscriptionDescription
        ]);
    }

    public function updateReward(
        $rewardId,
        $changerId,
        $name,
        $needDelivery,
        $price,
        $quantity,
        $description
    ) {
        return $this->put("reward/{$rewardId}", [
            'changer_id' => $changerId,
            'name' => $name,
            'need_delivery' => $needDelivery,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description
        ]);
    }

    public function updateReward2(
        $rewardId,
        $changerId,
        $name,
        $needDelivery,
        $price,
        $quantity,
        $hasOffline,
        $description
    ) {
        return $this->put("reward/{$rewardId}", [
            'changer_id' => $changerId,
            'name' => $name,
            'need_delivery' => $needDelivery,
            'price' => $price,
            'quantity' => $quantity,
            'has_offline' => $hasOffline,
            'description' => $description
        ]);
    }

    public function updateReward3(
        $rewardId,
        $changerId,
        $name,
        $needDelivery,
        $price,
        $quantity,
        $hasOffline,
        $description,
        $subscriptionName,
        $subscriptionPrice,
        $subscriptionDescription
    ) {
        return $this->put("reward/{$rewardId}", [
            'changer_id' => $changerId,
            'name' => $name,
            'need_delivery' => $needDelivery,
            'price' => $price,
            'quantity' => $quantity,
            'has_offline' => $hasOffline,
            'description' => $description,
            'subscription_name' => $subscriptionName,
            'subscription_price' => $subscriptionPrice,
            'subscription_description' => $subscriptionDescription
        ]);
    }

    public function updateReward4($changerId, $rewardId, $inputs = []) {
        $inputs = array_merge($inputs, ['changer_id' => $changerId]);
        return $this->put("reward/{$rewardId}", $inputs);
    }

    public function getReward($rewardId)
    {
        return $this->get("reward/{$rewardId}");
    }

    public function getRewardsByIds($rewardIds, $includeHidden = false)
    {
        if ($includeHidden) {
            return $this->get("reward/by_ids", ['ids' => implode(',', $rewardIds)]);
        } else {
            return $this->get("reward/by_ids", [
                'ids' => implode(',', $rewardIds),
                'is_visible' => 1
            ]);
        }
    }

    public function getRewardsByIds2($rewardIds, $filters = [])
    {
        $filters = array_merge($filters, [
            'ids' => implode(',', $rewardIds)
        ]);

        return $this->get("reward/by_ids", $filters);
    }

    public function getRewardsByProject($projectId, $includeHidden = false)
    {
        if ($includeHidden) {
            return $this->get("reward/project/{$projectId}");
        } else {
            return $this->get("reward/project/{$projectId}", [
                'is_visible' => 1
            ]);
        }
    }

    public function getRewardsByProject2($projectId, $filters = [])
    {
        return $this->get("reward/project/{$projectId}", $filters);
    }

    public function getAllRewardsByProject($projectId)
    {
        return $this->get("reward/project/{$projectId}", ['show_all' => true]);
    }

    public function getOfflineRewardsByProject($projectId, $includeHidden = false)
    {
        $filterArray = ['has_offline' => 1];

        if ($includeHidden == false) {
            $filterArray = array_merge($filterArray, [
                'is_visible' => 1,
            ]);
        }

        return $this->get("reward/project/{$projectId}", $filterArray);
    }

    public function getOfflineRewardsByProject2($projectId, $filters = [])
    {
        $filters['has_offline'] = 1;

        return $this->get("reward/project/{$projectId}", $filters);
    }

    public function getContentRewardsByProject($projectId, $includeHidden = false)
    {
        $filterArray = ['has_offline' => 0];

        if ($includeHidden == false) {
            $filterArray = array_merge($filterArray, ['is_visible' => 1]);
        }

        return $this->get("reward/project/{$projectId}", $filterArray);
    }

    public function getContentRewardsByProject2($projectId, $filters = [])
    {
        $filters['has_offline'] = 0;

        return $this->get("reward/project/{$projectId}", $filters);
    }


    public function toggleRewardActive($rewardId)
    {
        return $this->put("reward/{$rewardId}/toggle_active");
    }

    public function toggleRewardVisible($rewardId)
    {
        return $this->put("reward/{$rewardId}/toggle_visible");
    }


    public function updateRewardsOrderInProject($projectId, $rewardIds)
    {
        return $this->put("reward/project/{$projectId}", ['ids' => implode(',', $rewardIds)]);
    }

    /*
     * Content Related Functions
     */
    public function createContent($changerId, $title, $type)
    {
        return $this->post("content", [
            'title' => $title,
            'type' => $type,
            'changer_id' => $changerId
        ]);
    }

    public function createContent2($changerId, $title, $isPaid)
    {
        return $this->post("content", [
            'title' => $title,
            'is_paid' => $isPaid,
            'changer_id' => $changerId
        ]);
    }

    public function getContents($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("content", $filterArray);
    }

    public function getContentsBySet($setId, $filterArray = [])
    {
        return $this->get("content/set/{$setId}", $filterArray);
    }

    public function getContentsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("content/by_set_ids/", $filterArray);
    }

    public function updateContent($contentId, $isPaid)
    {
        return $this->put("content/{$contentId}", ['is_paid' => $isPaid]);
    }

    public function updateContent2($contentId, $isPaid, $readTime, $summary)
    {
        return $this->put("content/{$contentId}", [
            'is_paid' => $isPaid,
            'read_time' => $readTime,
            'summary' => $summary
        ]);
    }

    public function updateContent3(
        $contentId,
        $title,
        $status,
        $isPaid,
        $readTime,
        $startAt,
        $summary,
        $memo
    ) {
        return $this->put("content/{$contentId}", [
            'title' => $title,
            'status' => $status,
            'is_paid' => $isPaid,
            'read_time' => $readTime,
            'start_at' => $startAt,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }

    public function updateContent4(
        $changerId,
        $contentId,
        $title,
        $isActive,
        $isPaid,
        $image,
        $readTime,
        $publishAt,
        $summary,
        $memo
    ) {
        return $this->put("content/{$contentId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'is_active' => $isActive,
            'is_paid' => $isPaid,
            'read_time' => $readTime,
            'image' => $image,
            'publish_at' => $publishAt,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }

    public function updateContent5(
        $changerId,
        $contentId,
        $title,
        $isActive,
        $isPaid,
        $image,
        $readTime,
        $publishAt,
        $freeLength,
        $summary,
        $memo
    ) {
        return $this->put("content/{$contentId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'is_active' => $isActive,
            'is_paid' => $isPaid,
            'read_time' => $readTime,
            'image' => $image,
            'publish_at' => $publishAt,
            'free_length' => $freeLength,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }

    public function updateContentSet($contentId, $setId, $orderInSet)
    {
        return $this->put("content/{$contentId}/set", [
            'set_id' => $setId,
            'order_in_set' => $orderInSet
        ]);
    }

    public function updateContentSet2(
        $changerId,
        $contentId,
        $setId,
        $orderInSet
    ) {
        return $this->put("content/{$contentId}",
            [
                'changer_id' => $changerId,
                'set_id' => $setId,
                'order_in_set' => $orderInSet
            ]);
    }

    public function updateContentProjectId($contentId, $projectId)
    {
        return $this->put("content/{$contentId}/project", ['project_id' => $projectId]);
    }

    public function updateContentProjectId2($changerId, $contentId, $projectId)
    {
        return $this->put("content/{$contentId}",
            [
                'changer_id' => $changerId,
                'project_id' => $projectId
            ]);
    }

    public function updateContentsOrderInSet($setId, $contentIds)
    {
        return $this->put("content/set/{$setId}", ['ids' => implode(',', $contentIds)]);
    }

    public function updateContentsOrderInSet2($changerId, $setId, $contentIds)
    {
        return $this->put("content/set/{$setId}",
            [
                'changer_id' => $changerId,
                'ids' => implode(',', $contentIds)
            ]);
    }

    // deprecated
    public function updateContentCoverImage($contentId, $imageUrl)
    {
        return $this->put("content/{$contentId}/image", ['cover_image' => $imageUrl]);
    }

    // deprecated
    public function updateContentListImage($contentId, $imageUrl)
    {
        return $this->put("content/{$contentId}/image", ['list_image' => $imageUrl]);
    }

    // deprecated
    public function updateContentContentList(
        $changerId,
        $contentId,
        $contentListIdArray,
        $contentTitleArray,
        $contentArray,
        $randomStringIdArray
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'content_list_id' => implode(',', $contentListIdArray),
            'content_title' => $contentTitleArray,
            'content' => $contentArray,
            'random_string_id' => implode(',', $randomStringIdArray)
        ];

        return $this->put("content/{$contentId}/content_lists", $inputs);
    }

    public function getContent($contentId)
    {
        return $this->get("content/{$contentId}");
    }

    public function getContentsByIds($contentIds)
    {
        return $this->get("content/by_ids", ['ids' => implode(',', $contentIds)]);
    }

    public function getContentsByProject($projectId, $filterArray)
    {
        return $this->get("content/project/{$projectId}", $filterArray);
    }

    public function getTotalContentCount() {
        return $this->get("content/total");
    }

    public function getTotalSetCount() {
        return $this->get("set/total");
    }

    public function getTotalAuthorCount() {
        return $this->get("writer/total");
    }

    public function createContentItem($changerId, $contentId, $title)
    {
        return $this->post("content/{$contentId}/content_item", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function deleteContentItem($changerId, $contentId, $contentItemId)
    {
        return $this->post("content/{$contentId}/content_item/delete", [
            'changer_id' => $changerId,
            'content_item_id' => $contentItemId
        ]);
    }

    /*
     * Content Related Functions
     */
    public function getContentItemsByContent($contentId)
    {
        return $this->get("content_item/content/{$contentId}");
    }

    public function getContentItemsByContentIds($contentIds)
    {
        return $this->get("content_item/content_ids",
            ['content_ids' => implode(',', $contentIds)]
        );
    }

    public function updateContentItem(
        $changerId,
        $contentId,
        $contentItemIds,
        $contentItemTitles,
        $descriptions,
        $randomStringIds
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'content_item_ids' => implode(',', $contentItemIds),
            'content_item_titles' => $contentItemTitles,
            'content_item_description' => $descriptions,
            'random_string_ids' => implode(',', $randomStringIds)
        ];

        return $this->put("content_item/{$contentId}/content_items", $inputs);
    }

    // deprecated
    public function createContentList($changerId, $contentId, $title)
    {
        return $this->post("content/{$contentId}/content_list", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    // deprecated
    public function deleteContentList($changerId, $contentId, $listId)
    {
        return $this->post("content/{$contentId}/content_list/delete", [
            'changer_id' => $changerId,
            'list_id' => $listId
        ]);
    }

    /*
     * Content Related Functions
     */
    public function getContentListsByContent($contentId)
    {
        return $this->get("content_list/content/{$contentId}");
    }

    public function getContentListsByContentIds($contentIds)
    {
        return $this->get("content_list/content_ids",
            ['content_ids' => implode(',', $contentIds)]
        );
    }

    public function getContentListsByProject($projectId)
    {
        return $this->get("content_list/project/{$projectId}");
    }

    /*
     * Project Related Functions
     */

    public function createProject($changerId, $title)
    {
        return $this->post("project", [
            'title' => $title,
            'changer_id' => $changerId
        ]);
    }

    public function updateProject(
        $changerId,
        $projectId,
        $title,
        $status,
        $isPreorder,
        $isUnderConsideration,
        $startAt,
        $finishAt,
        $donateGoalPrice,
        $summary,
        $memo
    ) {
        return $this->put("project/{$projectId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'status' => $status,
            'is_preorder' => $isPreorder,
            'is_under_consideration' => $isUnderConsideration,
            'start_at' => $startAt,
            'finish_at' => $finishAt,
            'donate_goal_price' => $donateGoalPrice,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }

    public function updateProject2(
        $changerId,
        $projectId,
        $title,
        $isActive,
        $imageUrl,
        $imageVerticalUrl,
        $preorderStartAt,
        $preorderFinishAt,
        $preorderGoalPrice,
        $summary,
        $memo
    ) {
        return $this->put("project/{$projectId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'is_active' => $isActive,
            'image' => $imageUrl,
            'image_vertical' => $imageVerticalUrl,
            'preorder_start_at' => $preorderStartAt,
            'preorder_finish_at' => $preorderFinishAt,
            'preorder_goal_price' => $preorderGoalPrice,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }

    public function updateProject3(
        $changerId,
        $projectId,
        $title,
        $imageUrl,
        $imageVerticalUrl,
        $preorderStartAt,
        $preorderFinishAt,
        $preorderGoalPrice,
        $summary,
        $memo
    ) {
        return $this->put("project/{$projectId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'image' => $imageUrl,
            'image_vertical' => $imageVerticalUrl,
            'preorder_start_at' => $preorderStartAt,
            'preorder_finish_at' => $preorderFinishAt,
            'preorder_goal_price' => $preorderGoalPrice,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }

    public function updateProject4(
        $changerId,
        $projectId,
        $title,
        $imageUrl,
        $imageVerticalUrl,
        $preorderStartAt,
        $preorderFinishAt,
        $preorderGoalPrice,
        $basePrice,
        $summary,
        $memo )
    {
        return $this->put("project/{$projectId}", [ 'changer_id' => $changerId,
            'title' => $title,
            'image' => $imageUrl,
            'image_vertical' => $imageVerticalUrl,
            'preorder_start_at' => $preorderStartAt,
            'preorder_finish_at' => $preorderFinishAt,
            'preorder_goal_price' => $preorderGoalPrice,
            'base_price' => $basePrice,
            'summary' => $summary,
            'memo' => $memo
        ]);
    }


    public function updateProjectIsActive($changerId, $projectId, $isActive)
    {
        return $this->put("project/{$projectId}", [
            'changer_id' => $changerId,
            'is_active' => $isActive
        ]);
    }

    public function updateProjectStatus($changerId, $projectId, $status)
    {
        return $this->put("project/{$projectId}/status", [
            'changer_id' => $changerId,
            'status' => $status
        ]);
    }

    public function updateProjectStatusPaymentInProgress($changerId, $projectId)
    {
        return $this->put("project/{$projectId}/status", [
            'changer_id' => $changerId,
            'status' => PublyContentService::PROJECT_STATUS_PAYMENT_IN_PROGRESS
        ]);
    }

    public function updateProjectStatusPreorderDone($changerId, $projectId, $preorderSuccess)
    {
        return $this->put("project/{$projectId}/status", [
            'changer_id' => $changerId,
            'status' => PublyContentService::PROJECT_STATUS_PREORDER_DONE,
            'preorder_success' => $preorderSuccess
        ]);
    }

    public function updateProjectInactive($changerId, $projectId)
    {
        return $this->put("project/{$projectId}/is_active", [
            'changer_id' => $changerId,
            'is_active' => 0
        ]);
    }

    public function updateProjectActive($changerId, $projectId)
    {
        return $this->put("project/{$projectId}/is_active", [
            'changer_id' => $changerId,
            'is_active' => 1
        ]);
    }

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
        return $this->get("project/by_ids", ['ids' => implode(',', $projectIds)]);
    }

    public function getProjectsBySet($setId, $filterArray = [])
    {
        return $this->get("project/set/{$setId}", $filterArray);
    }

    public function getProjectsFinished($filterArray = [])
    {
        $filterArray['finished'] = true;
        return $this->get("project", $filterArray);
    }

    public function getProjectsFinished2($filterArray = [])
    {
        $filterArray['finished'] = true;
        $filterArray['is_active'] = 1;
        $filterArray['status'] = PublyContentService::PROJECT_STATUS_PREORDER;
        return $this->get("project", $filterArray);
    }

    public function createProjectSet($changerId, $projectId, $setId)
    {
        return $this->post("project/{$projectId}/set/{$setId}", ['changer_id' => $changerId]);
    }

    public function removeProjectSet($changerId, $projectId, $setId)
    {
        return $this->post("project/{$projectId}/set/{$setId}/delete", ['changer_id' => $changerId]);
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

    // deprecated
    public function updateProjectContent($changerId, $projectId, $content)
    {
        return $this->put("project/{$projectId}/content_list", [
            'changer_id' => $changerId,
            'content' => $content
        ]);
    }

    // deprecated
    public function updateProjectSections(
        $changerId,
        $projectId,
        $projectRecommnend,
        $projectAuthors,
        $projectDetail,
        $projectTableOfContents,
        $projectRewardDescription
    ) {
        return $this->put("project/{$projectId}/sections", [
            'changer_id' => $changerId,
            'project_recommend' => $projectRecommnend,
            'project_authors' => $projectAuthors,
            'project_detail' => $projectDetail,
            'project_table_of_contents' => $projectTableOfContents,
            'project_reward_description' => $projectRewardDescription
        ]);
    }

    // deprecated
    public function updateProjectSections2(
        $changerId,
        $projectId,
        $projectSummary,
        $projectTargetReader,
//                                           $projectRecommnend,
        $projectAuthors,
        $projectDetail,
        $projectTableOfContents,
        $projectRewardDescription
    ) {
        return $this->put("project/{$projectId}/sections",
            [
                'changer_id' => $changerId,
//              'project_recommend' => $projectRecommnend,
                'project_summary' => $projectSummary,
                'project_target_reader' => $projectTargetReader,
                'project_authors' => $projectAuthors,
                'project_detail' => $projectDetail,
                'project_table_of_contents' => $projectTableOfContents,
                'project_reward_description' => $projectRewardDescription
            ]);
    }

    public function updateProjectRewardDescription(
        $changerId,
        $projectId,
        $projectRewardDescription
    ) {
        return $this->put("project/{$projectId}/reward_description",
            [
                'changer_id' => $changerId,
                'reward_description' => $projectRewardDescription
            ]);
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
        return $this->put("project_progress", ['include_finished' => $includeFinished ? 1 : 0]);
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
     * Set Order Count Related Functions
     */
    public function getSetOrderCount($setId)
    {
        return $this->get("set_order_count/{$setId}");
    }

    public function updateAllSetOrderCounts()
    {
        return $this->put("set_order_count/all");
    }

    public function getSetOrderCountBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("set_order_count/by_set_ids", $filterArray);
    }

    /*
     * User Content Progress Related Functions
     */
    const USER_CONTENT_PROGRESS_COMPLETE = 'complete';
    const USER_CONTENT_PROGRESS_RESET_COMPLETE = 'reset_complete';

    public function updateOrCreateUserContentProgress(
        $userId,
        $contentId,
        $type,
        $action,
        $sectionIndex,
        $paragraphIndex
    ) {
        $inputs = [
            'action' => $action,
            'section_index' => $sectionIndex,
            'paragraph_index' => $paragraphIndex,
        ];

        return $this->put("user_content_progress/user/{$userId}/content/{$contentId}/type/{$type}", $inputs);
    }

    public function getLatestUserContentProgressByUserAndContent($userId, $contentId)
    {
        return $this->get("user_content_progress/user/{$userId}/content/{$contentId}/latest");
    }

    public function getLatestUserContentProgressByUserAndSet($userId, $setId)
    {
        return $this->get("user_content_progress/user/{$userId}/set/{$setId}/latest");
    }

    public function getLatestUserContentProgressByUserAndSet2($userId, $setId, $type)
    {
        $filterArray['type'] = $type;
        return $this->get("user_content_progress/user/{$userId}/set/{$setId}/latest", $filterArray);
    }

    public function getUserContentProgressByType($type, $page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("/user_content_progress/type/{$type}", $filterArray);
    }

    public function getUserContentProgressesByUserAndContentIds($userId, $contentIds)
    {
        $filterArray = [];
        $filterArray['content_ids'] = implode(',', $contentIds);
        return $this->get("user_content_progress/user/{$userId}/by_content_ids", $filterArray);
    }

    public function getUserContentProgressesByUserAndTypeAndContentIds($userId, $type, $contentIds)
    {
        $filterArray = [];
        $filterArray['content_ids'] = implode(',', $contentIds);
        return $this->get("user_content_progress/user/{$userId}/type/{$type}/by_content_ids", $filterArray);
    }

    public function getUserContentProgressesByUserAndTypeAndSetIds($userId, $type, $setIds)
    {
        $filterArray = [];
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("user_content_progress/user/{$userId}/type/{$type}/by_set_ids", $filterArray);
    }

    public function getUserContentProgressesByUser($userId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("user_content_progress/user/{$userId}", $filterArray);
    }

    public function getUserContentProgressesByUserAndType($userId, $type, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("user_content_progress/user/{$userId}/type/{$type}", $filterArray);
    }

    public function getUserContentProgress($userId, $contentId)
    {
        return $this->get("user_content_progress/user/{$userId}/content/{$contentId}");
    }

    public function getUserContentProgress2($userId, $contentId, $type)
    {
        return $this->get("user_content_progress/user/{$userId}/content/{$contentId}/type/{$type}");
    }

    public function getTotalUserContentProgressByContentAndType($contentId, $type)
    {
        return $this->get("user_content_progress/content/{$contentId}/type/{$type}/total");
    }

    public function getLatestUserContentProgressesByUserAndSets($userId, $setIds)
    {
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("user_content_progress/user/{$userId}/latest/by_set_ids", $filterArray);
    }

    public function getTotalUserContentProgressBySetAndType($setId, $type)
    {
        return $this->get("user_content_progress/set/{$setId}/type/{$type}/total");
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

    public function getSetsByProjects($projectIds, $filterArray = [])
    {
        $filterArray['project_ids'] = implode(',', $projectIds);
        return $this->get("/set/project_ids", $filterArray);
    }

    public function getSetsByCategory($categoryId, $filterArray = [])
    {
        return $this->get("set/category/{$categoryId}", $filterArray);
    }

    public function getSet($setId, $filterArray = [])
    {
        return $this->get("set/{$setId}", $filterArray);
    }

    public function createSet($changerId, $title)
    {
        return $this->post("set", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function updateSet($setId, $changerId, $title)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function updateSet2($setId, $changerId, $title, $publishAt)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'publish_at' => $publishAt
        ]);
    }

    public function updateSet3($setId, $changerId, $title, $summary, $publishAt)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'summary' => $summary,
            'publish_at' => $publishAt
        ]);
    }

    public function updateSet4($setId, $changerId, $title, $publishAt)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'publish_at' => $publishAt
        ]);
    }

    public function updateSet5($setId, $changerId, $title, $publishAt, $imageUrl, $squareImageUrl)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'publish_at' => $publishAt,
            'image_url' => $imageUrl,
            'square_image_url' => $squareImageUrl
        ]);
    }

    public function updateSetIsPackage($setId, $changerId, $isPackage)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'is_package' => $isPackage
        ]);
    }

    public function updateSetIsActive($setId, $changerId, $isActive, $publishAt)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'is_active' => $isActive,
            'publish_at' => $publishAt
        ]);
    }

    public function loadSetDataFromProject($changerId, $setId)
    {
        return $this->post("set/{$setId}/load_data_from_project", ['changer_id' => $changerId]);
    }

    public function updateSetImage($changerId, $setId, $imageUrl)
    {
        return $this->put("set/{$setId}/image", [
            'changer_id' => $changerId,
            'image_url' => $imageUrl
        ]);
    }

    public function updateSetSections(
        $changerId,
        $setId,
        $summary,
        $targetReader,
        $contentDetail,
        $tableOfContents,
        $authorsDescription
    ) {
        return $this->put("set/{$setId}",
            [
                'changer_id' => $changerId,
                'summary' => $summary,
                'target_reader' => $targetReader,
                'content_detail' => $contentDetail,
                'table_of_contents' => $tableOfContents,
                'authors_description' => $authorsDescription,
            ]);
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
        return $this->get("set_reader/total", [
            'user_id' => $userId,
            'set_id' => $setId
        ]);
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

    public function getSetReadersBySetId2($setId, $filterArray = [])
    {
        return $this->get("set_reader/set/{$setId}", $filterArray);
    }

    public function getSetReadersByUserId2($userId, $filterArray = [])
    {
        return $this->get("set_reader/user/{$userId}", $filterArray);
    }
    
    public function createSetReader($changerId, $userId, $setId, $sourceType, $adminId, $orderId, $note)
    {
        try {
            return $this->post("set_reader", [
                'changer_id' => $changerId,
                'user_id' => $userId,
                'set_id' => $setId,
                'source_type' => $sourceType,
                'admin_id' => $adminId,
                'order_id' => $orderId,
                'note' => $note
            ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function updateSetReader($setReaderId, $changerId, $note)
    {
        return $this->put("set_reader/{$setReaderId}", [
            'changer_id' => $changerId,
            'note' => $note
        ]);
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
        return $this->post("home_display", [
            'changer_id' => $changerId,
            'type' => $type,
            'project_id' => $projectId,
            'start_at' => $startAt,
            'finish_at' => $finishAt
        ]);
    }

    public function updateHomeDisplayTime($changerId, $homeDisplayId, $startAt, $finishAt)
    {
        return $this->put("home_display/{$homeDisplayId}", [
            'changer_id' => $changerId,
            'start_at' => $startAt,
            'finish_at' => $finishAt
        ]);
    }

    public function updateHomeDisplayOrder($changerId, $homeDisplayIds)
    {
        return $this->put("home_display/order", [
            'changer_id' => $changerId,
            'ids' => implode(',', $homeDisplayIds)
        ]);
    }

    public function deleteHomeDisplay($changerId, $homeDisplayId)
    {
        return $this->post("home_display/delete/", [
            'changer_id' => $changerId,
            'homeDisplayId' => $homeDisplayId
        ]);
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
        return $this->post("reply", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'content' => $content,
            'project_id' => $projectId,
            'content_id' => $contentId,
            'parent_id' => $parentId
        ]);
    }

    public function deleteReply($changerId, $replyId, $force = false)
    {
        return $this->post("reply/delete/", [
            'changer_id' => $changerId,
            'reply_id' => $replyId,
            'force' => $force ? 1 : 0
        ]);
    }

    public function updateReply($changerId, $replyId, $content, $force = false)
    {
        return $this->put("reply/{$replyId}/", [
            'changer_id' => $changerId,
            'content' => $content,
            'force' => $force ? 1 : 0
        ]);
    }

    public function addProjectAuthor($changerId, $projectId, $userId, $isHidden)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'project_id' => $projectId,
            'is_hidden' => $isHidden
        ]);
    }

    public function removeProjectAuthor($changerId, $projectId, $userId)
    {
        return $this->post("writer/delete/", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'project_id' => $projectId
        ]);
    }

    public function addContentAuthor($changerId, $contentId, $userId, $isHidden)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'content_id' => $contentId,
            'is_hidden' => $isHidden
        ]);
    }

    public function removeContentAuthor($changerId, $contentId, $userId)
    {
        return $this->post("writer/delete/", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'content_id' => $contentId
        ]);
    }

    public function addSetAuthor($changerId, $setId, $userId, $isHidden)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId,
            'is_hidden' => $isHidden
        ]);
    }

    public function removeSetAuthor($changerId, $setId, $userId)
    {
        return $this->post("writer/delete/", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId
        ]);
    }

    public function getProjectWriters($projectId, $filterArray = [])
    {
        return $this->get("writer/project/{$projectId}", $filterArray);
    }

    public function getContentWriters($contentId, $filterArray = [])
    {
        return $this->get("writer/content/{$contentId}", $filterArray);
    }

    public function getSetWriters($setId, $filterArray = [])
    {
        return $this->get("writer/set/{$setId}", $filterArray);
    }

    public function getWritersByProjectIds($projectIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $projectIds);
        return $this->get("writer/project/ids", $filterArray);
    }

    public function getWritersByContentIds($contentIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $contentIds);
        return $this->get("writer/content/ids", $filterArray);
    }

    public function getWritersBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("writer/set/ids", $filterArray);
    }

    public function getProjectLikesByProjectIds($projectIds, $filterArray = [])
    {
        $filterArray['project_ids'] = implode(',', $projectIds);
        return $this->get("project_like/by_project_ids", $filterArray);
    }

    public function addProjectLike($changerId, $userId, $projectId)
    {
        return $this->post("project_like", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'project_id' => $projectId
        ]);
    }

    public function removeProjectLike($changerId, $userId, $projectId)
    {
        return $this->post("project_like/delete", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'project_id' => $projectId
        ]);
    }

    public function removeProjectLikesByUser($changerId, $userId)
    {
        return $this->put("project_like/user/{$userId}/delete", [
            'changer_id' => $changerId
        ]);
    }

    public function getSetReviewsBySetId($setId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("/set_review/set/{$setId}", $filterArray);
    }

    public function getSetReviewsByIds($setReviewIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setReviewIds);
        return $this->get("/set_review/by_ids", $filterArray);
    }

    public function getSetReviewSummary($setId)
    {
        return $this->get("/set_review/set/{$setId}/summary");
    }

    public function getSetReview($userId, $setId, $filterArray = [])
    {
        return $this->get("/set_review/user/{$userId}/set/{$setId}", $filterArray);
    }

    public function getAllSetReviews()
    {
        $filterArray = [
            'page' => 1,
            'limit' => 0
        ];
        return $this->get("/set_review", $filterArray);
    }

    public function updateSetReview($changerId, $userId, $setId, $rating, $comment)
    {
        $inputs = ['changer_id' => $changerId];
        if ($rating) {
            $inputs['rating'] = $rating;
        }
        if ($comment) {
            $inputs['comment'] = $comment;
        }

        return $this->put("/set_review/user/{$userId}/set/{$setId}", $inputs);
    }

    public function updateSetReviewBySetAndUser($changerId, $userId, $setId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("/set_review/user/{$userId}/set/{$setId}", $inputs);
    }

    public function updateSetReview2($changerId, $setReviewId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("/set_review/{$setReviewId}", $inputs);
    }

    public function getAuthorGuides($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("/author_guide", $filterArray);
    }

    public function getAuthorGuide($authorGuideId)
    {
        return $this->get("/author_guide/{$authorGuideId}");
    }

    public function createAuthorGuide($changerId, $title)
    {
        return $this->post("/author_guide", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function updateAuthorGuide($changerId, $authorGuideId, $title, $text, $imageUrl)
    {
        return $this->put("/author_guide/{$authorGuideId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'text' => $text,
            'image_url' => $imageUrl
        ]);
    }

    public function updateAuthorGuideOrder($changerId, $authorGuideIds)
    {
        return $this->put("author_guide/order", [
            'changer_id' => $changerId,
            'ids' => implode(',', $authorGuideIds)
        ]);
    }

    public function updateAuthorGuideIsActive($changerId, $authorGuideId, $isActive)
    {
        return $this->put("author_guide/{$authorGuideId}/is_active", [
            'changer_id' => $changerId,
            'is_active' => $isActive
        ]);
    }

    public function getTextLibrariesByIds($ids, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $ids);
        return $this->get("text_library/ids", $filterArray);
    }

    /*
     * Curation Related Functions
     */
    public function getCurations($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("curation", $filterArray);
    }

    public function getCuration($curationId, $filterArray = [])
    {
        return $this->get("curation/{$curationId}", $filterArray);
    }

    public function createCuration($changerId, $title)
    {
        return $this->post("curation", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function updateCurationOrder($changerId, $curationIds)
    {
        return $this->put("curation/order", [
            'changer_id' => $changerId,
            'ids' => implode(',', $curationIds)
        ]);
    }

    public function updateCuration($changerId, $curationId, $title)
    {
        return $this->put("curation/{$curationId}", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function updateCuration2($changerId, $curationId, $title, $type)
    {
        return $this->put("curation/{$curationId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'type' => $type
        ]);
    }

    public function updateCurationisActive($changerId, $curationId, $isActive)
    {
        return $this->put("curation/{$curationId}", [
            'changer_id' => $changerId,
            'is_active' => $isActive
        ]);
    }

    public function deleteCuration($changerId, $curationId)
    {
        return $this->post("curation/{$curationId}/delete", ['changer_id' => $changerId]);
    }

    public function createSetCuration($changerId, $curationId, $setId)
    {
        return $this->post("set_curation/", [
            'changer_id' => $changerId,
            'set_id' => $setId,
            'curation_id' => $curationId
        ]);
    }

    public function removeSetCuration($changerId, $curationId, $setCurationId)
    {
        return $this->post("set_curation/{$setCurationId}/delete", [
            'changer_id' => $changerId,
            'curation_id' => $curationId
        ]);
    }

    public function getSetCurationsByCurationId($curationId, $filterArray)
    {
        return $this->get("set_curation/{$curationId}", $filterArray);
    }

    public function updateSetCurationOrder($changerId, $curationId, $setCurationIds)
    {
        return $this->put("set_curation/order", [
            'changer_id' => $changerId,
            'curation_id' => $curationId,
            'ids' => implode(',', $setCurationIds)
        ]);
    }

    public function createPackageReader($changerId, $userId, $planId, $sourceType, $adminId, $subscriptionId, $note)
    {
        try {
            return $this->post("package_reader", [
                'changer_id' => $changerId,
                'user_id' => $userId,
                'plan_id' => $planId,
                'source_type' => $sourceType,
                'admin_id' => $adminId,
                'subscription_id' => $subscriptionId,
                'note' => $note
            ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function createPackageReader2(
        $changerId,
        $userId,
        $planId,
        $sourceType,
        $adminId,
        $subscriptionId,
        $note,
        $settlementYear,
        $settlementMonth
    ) {
        try {
            return $this->post("package_reader", [
                'changer_id' => $changerId,
                'user_id' => $userId,
                'plan_id' => $planId,
                'source_type' => $sourceType,
                'admin_id' => $adminId,
                'subscription_id' => $subscriptionId,
                'note' => $note,
                'settlement_year' => $settlementYear,
                'settlement_month' => $settlementMonth
            ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function updateOrCreatePackageReader(
        $changerId,
        $userId,
        $planId,
        $sourceType,
        $adminId,
        $subscriptionId,
        $note,
        $settlementYear,
        $settlementMonth
    ) {
        try {
            return $this->put("package_reader/{$userId}", [
                'changer_id' => $changerId,
                'plan_id' => $planId,
                'source_type' => $sourceType,
                'admin_id' => $adminId,
                'subscription_id' => $subscriptionId,
                'note' => $note,
                'settlement_year' => $settlementYear,
                'settlement_month' => $settlementMonth
            ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function updateOrCreatePackageReader2(
        $changerId,
        $userId,
        $sourceType,
        $adminId,
        $subscriptionId,
        $couponUseHistoryId,
        $note
    ) {
        try {
            return $this->put("package_reader/{$userId}", [
                'changer_id' => $changerId,
                'source_type' => $sourceType,
                'admin_id' => $adminId,
                'subscription_id' => $subscriptionId,
                'coupon_use_history_id' => $couponUseHistoryId,
                'note' => $note
            ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function updateOrCreatePackageReader3(
        $changerId,
        $userId,
        $sourceType,
        $adminId,
        $subscriptionId,
        $couponUseHistoryId,
        $voucherUseHistoryId,
        $note,
        $settlementYear,
        $settlementMonth
    ) {
        try {
            return $this->put("package_reader/{$userId}", [
                'changer_id' => $changerId,
                'source_type' => $sourceType,
                'admin_id' => $adminId,
                'subscription_id' => $subscriptionId,
                'coupon_use_history_id' => $couponUseHistoryId,
                'voucher_use_history_id' => $voucherUseHistoryId,
                'note' => $note,
                'settlement_year' => $settlementYear,
                'settlement_month' => $settlementMonth
            ]);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }
    }

    public function deletePackageReader($params)
    {
        return $this->post("package_reader/delete", $params);
    }

    // deprecated
    public function getPackageReadersByUserId($userId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        $filterArray['user_id'] = $userId;
        return $this->get("package_reader/", $filterArray);
    }

    public function getPackageReadersByUser($userId, $filterArray = [])
    {
        return $this->get("package_reader/user/{$userId}", $filterArray);
    }

    public function getTotalPackageReader($userId)
    {
        return $this->get("package_reader/total", ['user_id' => $userId]);
    }

    public function addSetLike($changerId, $userId, $setId)
    {
        return $this->post("set_like", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId
        ]);
    }

    public function removeSetLike($changerId, $userId, $setId)
    {
        return $this->post("set_like/delete", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId
        ]);
    }

    public function getSetLikesByUser($userId, $filterArray = [])
    {
        $filterArray['user_id'] = $userId;
        return $this->get("set_like", $filterArray);
    }

    public function getSetLikesBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("set_like/by_set_ids", $filterArray);
    }

    public function getCouponExceptionSets($filterArray = [])
    {
        return $this->get("coupon_exception_set", $filterArray);
    }

    public function isCouponExceptionSet($setId)
    {
        $filterArray['set_id'] = $setId;
        $result = $this->get("coupon_exception_set", $filterArray);

        if (count($result['success']['data']) > 0) {
            return true;
        }

        return false;
    }

    public function createCouponExceptionSet($changerId, $setId)
    {
        return $this->post("coupon_exception_set", [
            'changer_id' => $changerId,
            'set_id' => $setId
        ]);
    }

    public function deleteCouponExceptionSet($changerId, $setId)
    {
        return $this->post("coupon_exception_set/delete", [
            'changer_id' => $changerId,
            'set_id' => $setId
        ]);
    }

    public function createCategory($changerId, $name)
    {
        return $this->post("category", [
            'changer_id' => $changerId,
            'name' => $name
        ]);
    }

    public function getCategories($filterArray = [])
    {
        return $this->get("category", $filterArray);
    }

    public function getCategory($categoryId)
    {
        return $this->get("category/{$categoryId}");
    }

    public function deleteCategory($changerId, $categoryId)
    {
        return $this->post("category/{$categoryId}/delete", [
            'changer_id' => $changerId
        ]);
    }

    public function getCategoriesBySet($setId, $filterArray = [])
    {
        return $this->get("category/set/{$setId}", $filterArray);
    }

    public function updateCategory($changerId, $categoryId, $name)
    {
        return $this->put("category/{$categoryId}", [
            'changer_id' => $changerId, 'name' => $name
        ]);
    }

    public function attachSetToCategory($changerId, $categoryId, $setId)
    {
        return $this->post("category/{$categoryId}/set/{$setId}/attach", ['changer_id' => $changerId]);
    }

    public function detachSetFromCategory($changerId, $categoryId, $setId)
    {
        return $this->post("category/{$categoryId}/set/{$setId}/detach", ['changer_id' => $changerId]);
    }

    public function getUserSetProgressesByUser($userId, $filterArray = [])
    {
        return $this->get("user_set_progress/user/{$userId}", $filterArray);
    }
}