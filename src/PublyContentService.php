<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyContentService extends BaseApiService
{
    const SET_REVIEW_IS_HIDDEN_FALSE = 0;
    const SET_REVIEW_IS_HIDDEN_TRUE = 1;

    const CURATION_TYPE_LIST = 1; //dep
    const CURATION_TYPE_CAROUSEL = 2; //dep

    const CURATION_TYPE_RANK_UNIQUE_SET_READER = 3;
    const CURATION_TYPE_INDIVIDUAL_RECOMMEND = 4;
    const CURATION_TYPE_MANUAL = 5;
    const CURATION_TYPE_LIKE_SET = 6;
    const CURATION_TYPE_CONTINUE_TO_READ = 7;
    const CURATION_TYPE_NEW_CONTENT = 8;
    const CURATION_TYPE_PUBLISH_BEFORE = 9;
    const CURATION_TYPE_SET_DRAFT = 10;
    const CURATION_TYPE_GUIDE = 11;

    const STRING_CURATION_TYPE = [
        PublyContentService::CURATION_TYPE_RANK_UNIQUE_SET_READER => '최근 인기 콘텐츠',
    ];

    const SET_READER_SOURCE_TYPE_ADMIN = 1;
    const SET_READER_SOURCE_TYPE_ORDER_SINGLE = 2;
    const SET_READER_SOURCE_TYPE_ORDER_BUNDLE = 3;

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

    const PROJECT_PAGE_BASE_ON_TEXT = 1;
    const PROJECT_PAGE_BASE_ON_VIDEO = 2;

    const USER_CONTENT_PROGRESS_TYPE_INDIVIDUAL = 1;
    const USER_CONTENT_PROGRESS_TYPE_PACKAGE = 2;

    const NO_PAGE_LIMIT = 0;

    const SET_STATUS_IN_PROGRESS = 1;
    const SET_STATUS_PUBLISHED = 2;
    const SET_STATUS_UNPUBLISHED = 3;
    const SET_STATUS_DRAFT = 4;

    const SET_DRAFT_STATUS_IN_PROGRESS = 1;
    const SET_DRAFT_STATUS_FAILED = 2;
    const SET_DRAFT_STATUS_SUCCEEDED = 3;
    const SET_DRAFT_STATUS_DROPPED = 4;
    const SET_DRAFT_STATUS_MAX = 5;

    const SET_TYPE_WEB_BOOK = 1;
    const SET_TYPE_ARTICLE = 2;
    const SET_TYPE_ON_AIR = 3;

    const CONTENT_TYPE_TEXT = 1;
    const CONTENT_TYPE_VIDEO = 2;

    const PROJECT_TYPE_SINGLE = 1;
    const PROJECT_TYPE_BUNDLE = 2;

    const CURATION_CONTENT_TYPE_SET = 1;
    const CURATION_CONTENT_TYPE_CONTENT = 2;
    const CURATION_CONTENT_TYPE_SET_DRAFT = 3;

    const CURATION_LAYOUT_TYPE_ONE_COLUMN = 1;
    const CURATION_LAYOUT_TYPE_TWO_COLUMN = 2;
    const CURATION_LAYOUT_TYPE_SCROLL = 3;
    const CURATION_LAYOUT_TYPE_SWIPE = 4;

    const CURATION_COMPOSITION_TYPE_MANUAL = 1;
    const CURATION_COMPOSITION_TYPE_AUTO = 2;
    const CURATION_COMPOSITION_TYPE_INDIVIDUAL_RECOMMEND = 3;
    const CURATION_COMPOSITION_TYPE_CONTINUE_TO_READ = 4;

    const SET_CAREER_TYPE_JUNIOR_HANDS_ON_WORKER = 1;
    const SET_CAREER_TYPE_SENIOR_HANDS_ON_WORKER = 2;
    const SET_CAREER_TYPE_MANAGER = 3;
    const SET_CAREER_TYPE_DECISION_MAKER = 4;

    const USER_SEGMENT_MANAGEMENT_LEVEL_HANDS_ON_WORKER = 1;
    const USER_SEGMENT_MANAGEMENT_LEVEL_MANAGER = 2;
    const USER_SEGMENT_MANAGEMENT_LEVEL_DECISION_MAKER = 3;

    const STRING_MANAGEMENT_LEVEL = [
        PublyContentService::USER_SEGMENT_MANAGEMENT_LEVEL_HANDS_ON_WORKER => '실무자',
        PublyContentService::USER_SEGMENT_MANAGEMENT_LEVEL_MANAGER => '사람/조직 관리자',
        PublyContentService::USER_SEGMENT_MANAGEMENT_LEVEL_DECISION_MAKER => '의사 결정권자',
    ];

    const GUIDE_ITEM_TYPE_SET = 1;
    const GUIDE_ITEM_TYPE_CONTENT = 2;

    const WRITER_TYPE_AUTHOR = 1;
    const WRITER_TYPE_CONTENT_PROVIDER = 6;

    const PROJECT_DESCRIPTION_TYPE_INTRODUCTION = 1;
    const PROJECT_DESCRIPTION_TYPE_CURRICULUM = 2;
    const PROJECT_DESCRIPTION_TYPE_FAQ = 3;

    const PAGE_VIEW_COUNT_TYPE_ONAIR_INDEX = 1;

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
        $price,
        $quantity,
        $description,
        $basePrice,
        $priceDescription
    ) {
        return $this->post("reward", [
            'changer_id' => $changerId,
            'project_id' => $projectId,
            'name' => $name,
            'price' => $price,
            'quantity' => $quantity,
            'description' => $description,
            'base_price' => $basePrice,
            'price_description' => $priceDescription
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

    public function getRewardsByProjectIds($projectIds, $filters = [])
    {
        $filters['project_ids'] = implode(',', $projectIds);

        return $this->get("reward/by_project_ids", $filters);
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


    public function updateRewardsOrderInProject($changerId, $projectId, $rewardIds)
    {
        return $this->put(
            "reward/project/{$projectId}",
            [
                'changer_id' => $changerId,
                'ids' => implode(',', $rewardIds)
            ]);
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

    public function createContent3($changerId, $title, $isPaid, $type)
    {
        return $this->post("content", [
            'title' => $title,
            'is_paid' => $isPaid,
            'type' => $type,
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

    public function getContentsByContentGroup($contentGroup, $filterArray = [])
    {
        return $this->get("content/content_group/{$contentGroup}", $filterArray);
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

    public function updateContent6(
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
        $canonicalUrl,
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
            'canonical_url' => $canonicalUrl,
            'memo' => $memo
        ]);
    }

    public function updateContent7(
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
        $canonicalUrl,
        $memo,
        $curationTitle
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
            'canonical_url' => $canonicalUrl,
            'memo' => $memo,
            'curation_title' => $curationTitle
        ]);
    }

    public function updateContent8(
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
        $canonicalUrl,
        $memo,
        $curationTitle,
        $type
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
            'canonical_url' => $canonicalUrl,
            'memo' => $memo,
            'curation_title' => $curationTitle,
            'type' => $type
        ]);
    }

    public function updateContent9(
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
        $canonicalUrl,
        $memo,
        $curationTitle,
        $type,
        $metaKeywords,
        $imgAlt
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
            'canonical_url' => $canonicalUrl,
            'memo' => $memo,
            'curation_title' => $curationTitle,
            'type' => $type,
            'meta_keywords' => $metaKeywords,
            'img_alt' => $imgAlt
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

    public function updateContentSetAndContentGroup(
        $changerId,
        $contentId,
        $setId,
        $contentGroupId,
        $orderInContentGroup
    ) {
        return $this->put("content/{$contentId}",
            [
                'changer_id' => $changerId,
                'set_id' => $setId,
                'order_in_content_group' => $orderInContentGroup,
                'content_group_id' => $contentGroupId
            ]);
    }

    public function updateContentIsPicked(
        $changerId,
        $contentId,
        $isPicked
    ) {
        return $this->put("content/{$contentId}",
            [
                'changer_id' => $changerId,
                'is_picked' => $isPicked
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

    public function updateContentsOrderInContentGroup($changerId, $contentGroupId, $contentIds)
    {
        return $this->put("content/content_group/{$contentGroupId}",
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

    public function getTotalContentCount2() {
        return $this->get("content/total")['success']['data'];
    }

    public function getTotalSetCount() {
        return $this->get("set/total");
    }

    public function getTotalAuthorCount() {
        return $this->get("writer/total");
    }

    public function getTotalAuthorCount2($filterArray = []) {
        return $this->get("writer/total", $filterArray)['success']['data'];
    }

    public function getTotalContentCountFromCache() {
        $cacheKey = 'TOTAL_CONTENT_COUNT';
        $totalContentCount = \Cache::remember($cacheKey, 60, function() {
            return $this->getTotalContentCount2();
        });

        return $totalContentCount;
    }

    public function getTotalPackageSetCountFromCache() {
        $cacheKey = 'TOTAL_PACKAGE_SET_COUNT';
        $latestPackageSetFilter =
            [
                'is_package' => 1,
                'status' => 2,
            ];
        $totalPackageSetCount = \Cache::remember($cacheKey, 60, function() use ($latestPackageSetFilter) {
            $latestPackageSetResult = $this->getSets(1, 1, $latestPackageSetFilter);
            $totalPackageSetCount = $latestPackageSetResult['paginator']['total_count'];

            return $totalPackageSetCount;
        });

        return $totalPackageSetCount;
    }

    public function getTotalWebBookSetCountFromCache() {
        $cacheKey = 'TOTAL_WEB_BOOK_SET_COUNT';
        $webBookSetFilter =
            [
                'is_package' => 1,
                'status' => 2,
                'type' => self::SET_TYPE_WEB_BOOK,
            ];
        $totalWebBookSetCount = \Cache::remember($cacheKey, 60, function() use ($webBookSetFilter) {
            $webBookSetResult = $this->getSets(1, 1, $webBookSetFilter);
            $totalWebBookSetCount = $webBookSetResult['paginator']['total_count'];

            return $totalWebBookSetCount;
        });

        return $totalWebBookSetCount;
    }

    public function getTotalArticleSetCountFromCache() {
        $cacheKey = 'TOTAL_ARTICLE_SET_COUNT';
        $articleSetFilter =
            [
                'is_package' => 1,
                'status' => 2,
                'type' => self::SET_TYPE_ARTICLE
            ];
        $totalArticleSetCount = \Cache::remember($cacheKey, 60, function() use ($articleSetFilter) {
            $articleSetResult = $this->getSets(1, 1, $articleSetFilter);
            $totalArticleSetCount = $articleSetResult['paginator']['total_count'];

            return $totalArticleSetCount;
        });

        return $totalArticleSetCount;
    }

    public function getTotalAuthorCountFromCache($filterArray = []) {
        $cacheKey = 'TOTAL_AUTHOR_COUNT';
        $totalAuthorCount = \Cache::remember($cacheKey, 60, function() use ($filterArray) {
            return $this->getTotalAuthorCount2($filterArray);
        });

        return $totalAuthorCount;
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

    public function createProject($changerId, $title, $type)
    {
        return $this->post("project", [
            'title' => $title,
            'type' => $type,
            'changer_id' => $changerId
        ]);
    }

    public function updateProject(
        $changerId,
        $projectId,
        $title,
        $imageUrl,
        $imageVerticalUrl,
        $preorderStartAt,
        $preorderFinishAt,
        $preorderGoalPrice,
        $preorderGoalCount,
        $basePrice,
        $summary,
        $memo,
        $type,
        $guideId,
        $pageBase
    ) {
        return $this->put("project/{$projectId}", [ 'changer_id' => $changerId,
            'title' => $title,
            'image' => $imageUrl,
            'image_vertical' => $imageVerticalUrl,
            'preorder_start_at' => $preorderStartAt,
            'preorder_finish_at' => $preorderFinishAt,
            'preorder_goal_price' => $preorderGoalPrice,
            'preorder_goal_count' => $preorderGoalCount,
            'base_price' => $basePrice,
            'summary' => $summary,
            'memo' => $memo,
            'type' => $type,
            'guide_id' => $guideId,
            'page_base' => $pageBase
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

    public function getProjectsByIds($projectIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $projectIds);

        return $this->get("project/by_ids", $filterArray);
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

    public function createProjectSet($projectId, $setId, $settlementRate)
    {
        return $this->post("project_set", [
            'project_id' => $projectId,
            'set_id' => $setId,
            'settlement_rate' => $settlementRate
        ]);
    }

    public function removeProjectSet($projectSetId)
    {
        return $this->post("project_set/{$projectSetId}/delete");
    }

    public function updateProjectSet($projectSetId, $settlementRate)
    {
        return $this->put("project_set/{$projectSetId}", [
            'settlement_rate' => $settlementRate
        ]);
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

    public function getSetCountsOfEachType($filterArray = [])
    {
        return $this->get("set/count_of_each_type", $filterArray);
    }

    public function createSet($changerId, $title)
    {
        return $this->post("set", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function createSet2($changerId, $title, $type)
    {
        return $this->post("set", [
            'changer_id' => $changerId,
            'title' => $title,
            'type' => $type
        ]);
    }

    public function updateSet(
        $changerId,
        $setId,
        $title,
        $publishAt,
        $imageUrl,
        $squareImageUrl,
        $note,
        $description,
        $type,
        $isVisible,
        $metaKeywords,
        $imgAlt
    ) {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'publish_at' => $publishAt,
            'image_url' => $imageUrl,
            'square_image_url' => $squareImageUrl,
            'note' => $note,
            'description' => $description,
            'type' => $type,
            'is_visible' => $isVisible,
            'meta_keywords' => $metaKeywords,
            'img_alt' => $imgAlt
        ]);
    }

    public function progressSet($changerId, $setId)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'action' => 'progress'
        ]);
    }

    public function publishSet($changerId, $setId)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'action' => 'publish'
        ]);
    }

    public function unpublishSet($changerId, $setId)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'action' => 'unpublish'
        ]);
    }

    public function updateSetIsPackage($changerId, $setId, $isPackage)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'is_package' => $isPackage
        ]);
    }

    public function updateSetIsVisible($changerId, $setId, $isVisible)
    {
        return $this->put("set/{$setId}", [
            'changer_id' => $changerId,
            'is_visible' => $isVisible
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

    public function getTotalSetReader($userId, $setId, $filterArray = [])
    {
        $filterArray['user_id'] = $userId;
        $filterArray['set_id'] = $setId;

        return $this->get("set_reader/total", $filterArray);
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

    //deprecated
    public function addProjectAuthor($changerId, $projectId, $userId, $isHidden)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'project_id' => $projectId,
            'is_hidden' => $isHidden
        ]);
    }


    public function addProjectWriter($changerId, $projectId, $profileId, $writerTypeId, $isMain)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'profile_id' => $profileId,
            'project_id' => $projectId,
            'writer_type_id' => $writerTypeId,
            'is_main' => $isMain
        ]);
    }

    //deprecated
    public function removeProjectAuthor($changerId, $projectId, $userId)
    {
        return $this->post("writer/delete/", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'project_id' => $projectId
        ]);
    }

    //deprecated
    public function addContentAuthor($changerId, $contentId, $userId, $isHidden)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'content_id' => $contentId,
            'is_hidden' => $isHidden
        ]);
    }

    public function addContentWriter($changerId, $contentId, $profileId, $writerTypeId, $isMain)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'profile_id' => $profileId,
            'content_id' => $contentId,
            'writer_type_id' => $writerTypeId,
            'is_main' => $isMain
        ]);
    }

    //deprecated
    public function removeContentAuthor($changerId, $contentId, $userId)
    {
        return $this->post("writer/delete/", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'content_id' => $contentId
        ]);
    }

    //deprecated
    public function addSetAuthor($changerId, $setId, $userId, $isHidden)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId,
            'is_hidden' => $isHidden
        ]);
    }

    public function addSetWriter($changerId, $setId, $profileId, $isMain, $writerTypeId)
    {
        return $this->post("writer", [
            'changer_id' => $changerId,
            'profile_id' => $profileId,
            'set_id' => $setId,
            'is_main' => $isMain,
            'writer_type_id' => $writerTypeId
        ]);
    }

    //deprecated
    public function removeSetAuthor($changerId, $setId, $userId)
    {
        return $this->post("writer/delete/", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId
        ]);
    }

    public function updateWriterOrder($changerId, $ids)
    {
        return $this->put('writer/update_order', [
            'changer_id' => $changerId,
            'ids' => implode(',', $ids)
        ]);
    }

    public function updateWriter($changerId, $writerId, $writerTypeId, $isMain)
    {
        return $this->put("writer/{$writerId}", [
            'changer_id' => $changerId,
            'writer_type_id' => $writerTypeId,
            'is_main' => $isMain
        ]);
    }

    public function removeWriter($changerId, $writerId)
    {
        return $this->post("writer/{$writerId}/delete/", [
            'changer_id' => $changerId
        ]);
    }

    public function removeWriterByProfile($changerId, $profileId)
    {
        return $this->post("/writer/profile/{$profileId}/delete", [
            'changer_id' => $changerId
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

    public function getWritersByProfileId($profileId, $filterArray = [])
    {
        return $this->get("writer/profile/{$profileId}", $filterArray);
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

    public function getSetReviewsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("/set_review/by_set_ids", $filterArray);
    }

    public function getSetReviewSummary($setId)
    {
        return $this->get("/set_review/set/{$setId}/summary");
    }

    public function getSetReviewsAverageRatingBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);
        return $this->get("/set_review/average_rating/by_set_ids", $filterArray);
    }

    public function getSetReview($userId, $setId, $filterArray = [])
    {
        return $this->get("/set_review/user/{$userId}/set/{$setId}", $filterArray);
    }

    public function getSetReviewByReviewId($setReviewId)
    {
        return $this->get("/set_review/{$setReviewId}");
    }

    public function getAllSetReviews()
    {
        $filterArray = [
            'page' => 1,
            'limit' => 0
        ];
        return $this->get("/set_review", $filterArray);
    }

    public function getSetReviewCountBySetId($setId, $filterArray = [])
    {
        return $this->get("/set_review/set/{$setId}/count", $filterArray);
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

    public function getCurationsByIds($curationIds, $filterArray = [])
    {
        $filterArray['ids'] = $curationIds;
        return $this->get("curation/by_ids", $filterArray);
    }

    //deprecated
    public function createCuration($changerId, $title)
    {
        return $this->post("curation", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function createCuration2($changerId, $title, $summary)
    {
        return $this->post("curation", [
            'changer_id' => $changerId,
            'title' => $title,
            'summary' => $summary
        ]);
    }

    public function createCuration3($changerId, $title, $summary, $contentType)
    {
        return $this->post("curation", [
            'changer_id' => $changerId,
            'title' => $title,
            'summary' => $summary,
            'content_type' => $contentType
        ]);
    }

    public function createCuration4($changerId, $title, $summary, $type)
    {
        return $this->post("curation", [
            'changer_id' => $changerId,
            'title' => $title,
            'summary' => $summary,
            'type' => $type
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

    public function updateCuration3($changerId, $curationId, $title, $type, $summary)
    {
        return $this->put("curation/{$curationId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'type' => $type,
            'summary' => $summary
        ]);
    }

    public function updateCuration4($changerId, $curationId, $title, $summary, $layoutType, $compositionType)
    {
        return $this->put("curation/{$curationId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'layout_type' => $layoutType,
            'composition_type' => $compositionType,
            'summary' => $summary
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

    public function getPackageReadersByUserIds($userIds, $page = 1, $limit = 500, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        $filterArray['user_ids'] = implode(',', $userIds);
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

    public function addSetLike($changerId, $userId, $setId, $contentId = null)
    {
        return $this->post("set_like", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId
        ]);
    }

    public function removeSetLike($changerId, $userId, $setId, $contentId = null)
    {
        return $this->post("set_like/delete", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId
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

    public function getSetLikesByContentIds($contentIds, $filterArray = [])
    {
        $filterArray['content_ids'] = implode(',', $contentIds);
        return $this->get("set_like/by_content_ids", $filterArray);
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

    public function getCategoriesByIds($categoryIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $categoryIds);
        return $this->get("category/by_ids", $filterArray);
    }

    public function getCategoriesByIds2($categoryIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $categoryIds);
        return $this->get("category/by_ids", $filterArray)['success']['data'];
    }

    public function updateCategory($changerId, $categoryId, $name)
    {
        return $this->put("category/{$categoryId}", [
            'changer_id' => $changerId, 'name' => $name
        ]);
    }

    public function updateCategory2($changerId, $categoryId, $name, $mastheadImageUrl)
    {
        return $this->put("category/{$categoryId}", [
            'changer_id' => $changerId, 'name' => $name, 'masthead_image_url' => $mastheadImageUrl
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

    public function getUserSetProgressesByUser2($userId, $filterArray = [])
    {
        return $this->get("user_set_progress/user/with_pagination/{$userId}", $filterArray);
    }

    public function getUserSetProgressByUserAndSet($userId, $setId)
    {
        return $this->get("user_set_progress/user/{$userId}/set/{$setId}");
    }

    public function createCategoryOrder($changerId, $categoryId)
    {
        return $this->post("category_order",
            [
                'category_id' => $categoryId,
                'changer_id' => $changerId
            ]);
    }

    public function getCategoryOrders($filterArray = [])
    {
        return $this->get("category_order", $filterArray);
    }

    public function getCategoryOrders2($filterArray = [])
    {
        return $this->get("category_order", $filterArray)['success']['data'];
    }

    public function deleteCategoryOrder($changerId, $categoryOrderId)
    {
        return $this->post("category_order/{$categoryOrderId}/delete",
            [
                'changer_id' => $changerId
            ]);
    }

    public function updateCategoryOrderOrder($changerId, $categoryOrderIds)
    {
        return $this->put("category_order/update_order",
            [
                'changer_id'=> $changerId,
                'ids' => implode(',', $categoryOrderIds)
            ]);
    }

    public function getSortedCategoriesFromCache()
    {
        $cacheKey = 'SORTED_CATEGORIES';

        $sortedCategories = \Cache::remember($cacheKey, 60, function () {
            $takeLimit = 29;
            $filterArray = ['order' => 'asc', 'take_limit' => $takeLimit];

            $categoryOrders = $this->getCategoryOrders2($filterArray);
            $categoryIds = array_unique_values_from_second_dimension($categoryOrders, 'category_id');

            $categories = $this->getCategoriesByIds2($categoryIds);
            $categories = array_make_key_from_second_dimension($categories, 'id');

            $sortedCategories = [];
            foreach ($categoryOrders as $categoryOrder) {
                if (isset($categories[$categoryOrder['category_id']])) {
                    array_push($sortedCategories, $categories[$categoryOrder['category_id']]);
                }
            }

            return $sortedCategories;
        });

        return $sortedCategories;
    }

    public function getOnboardingSets($filterArray = [])
    {
        return $this->get("onboarding_set", $filterArray);
    }

    public function createOnboardingSet($setId)
    {
        return $this->post("onboarding_set",
            [
                'set_id' => $setId
            ]);
    }

    public function deleteOnboardingSet($setId)
    {
        return $this->post("onboarding_set/{$setId}/delete");
    }
    public function updateOnboardingSetOrder($onboardingSetIds)
    {
        return $this->put("onboarding_set/update_order",
            [
                'ids' => implode(',', $onboardingSetIds)
            ]);
    }

    public function getOnboardingCategories($filterArray = [])
    {
        return $this->get("onboarding_category", $filterArray);
    }

    public function createOnboardingCategory($categoryId)
    {
        return $this->post("onboarding_category",
            [
                'category_id' => $categoryId
            ]);
    }

    public function deleteOnboardingCategory($categoryId)
    {
        return $this->post("onboarding_category/{$categoryId}/delete");
    }

    public function updateOnboardingCategoryOrder($onboardingCategoryIds)
    {
        return $this->put("onboarding_category/update_order",
            [
                'ids' => implode(',', $onboardingCategoryIds)
            ]);
    }

    public function getProfiles($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("profile", $filterArray);
    }

    public function getProfile($profileId)
    {
        return $this->get("profile/{$profileId}");
    }

    public function getProfilesByIds($profileIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $profileIds);
        return $this->get("profile/by_ids", $filterArray);
    }

    public function getProfilesByUserIds($userIds, $filterArray = [])
    {
        $filterArray['user_ids'] = implode(',', $userIds);
        return $this->get("profile/by_user_ids", $filterArray);
    }

    public function createProfile($changerId, $name)
    {
        $inputs = [
            'changer_id' => $changerId,
            'name' => $name
        ];

        return $this->post("profile", $inputs);
    }

    public function updateProfile(
        $changerId,
        $profileId,
        $name,
        $imageUrl,
        $title,
        $description,
        $shortDescription,
        $note,
        $links,
        $userId,
        $hashtags
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'name' => $name,
            'image_url' => $imageUrl,
            'title' => $title,
            'description' => $description,
            'short_description' => $shortDescription,
            'note' => $note,
            'links' => $links,
            'user_id' => $userId,
            'hashtags' => $hashtags
        ];

        return $this->put("profile/{$profileId}", $inputs);
    }

    public function deleteProfile($changerId, $profileId)
    {
        $inputs = ['changer_id' => $changerId];

        return $this->post("profile/{$profileId}/delete", $inputs);
    }

    public function getWriterTypes($filterArray = [])
    {
        return $this->get("writer_type", $filterArray);
    }

    public function getWriterTypesByIds($writerIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $writerIds);
        return $this->get("writer_type/by_ids", $filterArray);
    }

    public function getWriterType($writerTypeId)
    {
        return $this->get("writer_type/{$writerTypeId}");
    }

    public function createWriterType($name)
    {
        $inputs = [
            'name' => $name
        ];

        return $this->post("writer_type", $inputs);
    }

    public function updateWriterType($writerTypeId, $name)
    {
        $inputs = [
            'name' => $name
        ];

        return $this->put("writer_type/{$writerTypeId}", $inputs);
    }

    public function deleteWriterType($writerTypeId)
    {
        return $this->post("writer_type/{$writerTypeId}/delete");
    }

    public function createPermission($userId, $setId, $contentId, $projectId, $readable, $editable)
    {
        $inputs = [
            'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId,
            'project_id' => $projectId,
            'readable' => $readable,
            'editable' => $editable
        ];
        return $this->post("permission", $inputs);
    }

    public function updatePermission($permissionId, $readable, $editable)
    {
        $inputs = [
            'readable' => $readable,
            'editable' => $editable
        ];
        return $this->put("permission/{$permissionId}", $inputs);
    }

    public function deletePermission($permissionId)
    {
        return $this->post("permission/{$permissionId}/delete");
    }

    public function getPermissionByUserAndSet($userId, $setId)
    {
        return $this->get("permission/user/{$userId}/set/{$setId}");
    }

    public function getPermissionByUserAndContent($userId, $contentId)
    {
        return $this->get("permission/user/{$userId}/content/{$contentId}");
    }

    public function getPermissionByUserAndProject($userId, $projectId)
    {
        return $this->get("permission/user/{$userId}/project/{$projectId}");
    }

    public function getPermissionsBySet($setId, $filterArray = [])
    {
        return $this->get("permission/set/{$setId}", $filterArray);
    }

    public function getPermissionsByContent($contentId, $filterArray = [])
    {
        return $this->get("permission/content/{$contentId}", $filterArray);
    }

    public function getPermissionsByProject($projectId, $filterArray = [])
    {
        return $this->get("permission/project/{$projectId}", $filterArray);
    }

    public function createSetDraft($changerId, $setId, $title, $goalLikeNumber, $summary, $finishDate)
    {
        return $this->post("set_draft", [
            'changer_id' => $changerId,
            'set_id' => $setId,
            'title' => $title,
            'goal_like_number' => $goalLikeNumber,
            'summary' => $summary,
            'finish_date' => $finishDate
        ]);
    }

    public function updateSetDraft($changerId, $setDraftId, $title, $goalLikeNumber, $summary, $finishDate)
    {
        return $this->put("set_draft/{$setDraftId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'action' => 'modify',
            'goal_like_number' => $goalLikeNumber,
            'summary' => $summary,
            'finish_date' => $finishDate
        ]);
    }

    public function succeedSetDraft($changerId, $setDraftId)
    {
        return $this->put("set_draft/{$setDraftId}", [
            'changer_id' => $changerId,
            'action' => 'succeed'
        ]);
    }

    public function failSetDraft($changerId, $setDraftId)
    {
        return $this->put("set_draft/{$setDraftId}", [
            'changer_id' => $changerId,
            'action' => 'fail'
        ]);
    }

    public function dropSetDraft($changerId, $setDraftId)
    {
        return $this->put("set_draft/{$setDraftId}", [
            'changer_id' => $changerId,
            'action' => 'drop'
        ]);
    }

    public function getSetDraft($setDraftId)
    {
        return $this->get("set_draft/{$setDraftId}");
    }

    public function getSetDrafts($filterArray = [])
    {
        return $this->get("set_draft", $filterArray);
    }

    public function getSetDraftLikesByUser($userId, $filterArray = [])
    {
        return $this->get("set_draft_like/user/{$userId}", $filterArray);
    }

    public function getSetDraftLikesBySetDraft($setDraftId, $filterArray = [])
    {
        return $this->get("set_draft_like/set_draft/{$setDraftId}", $filterArray);
    }

    public function getSetDraftLikesBySetDraftIds($setDraftIds, $filterArray = [])
    {
        $filterArray['set_draft_ids'] = implode(',', $setDraftIds);

        return $this->get("set_draft_like/by_set_draft_ids", $filterArray);
    }

    public function getSetDraftLikes($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("set_draft_like", $filterArray);
    }

    public function addSetDraftLike($changerId, $userId, $setDraftId)
    {
        return $this->post("set_draft_like", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_draft_id' => $setDraftId
        ]);
    }

    public function removeSetDraftLike($changerId, $userId, $setDraftId)
    {
        return $this->post("set_draft_like/delete", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_draft_id' => $setDraftId
        ]);
    }

    public function getSetDraftLikeBySetDraftAndUser($setDraftId, $userId)
    {
        return $this->get("set_draft_like/set_draft/{$setDraftId}/user/{$userId}");
    }

    public function updateAllSetDraftStatus($changerId)
    {
        return $this->post("set_draft/update_all_status", [
            'changer_id' => $changerId
        ]);
    }

    public function createContentCuration($curationId, $contentId)
    {
        return $this->post("content_curation", [
            'curation_id' => $curationId,
            'content_id' => $contentId
        ]);
    }

    public function updateContentCurationOrder($curationId, $contentCurationIds)
    {
        return $this->put("content_curation/order", [
            'curation_id' => $curationId,
            'ids' => implode(',', $contentCurationIds)
        ]);
    }

    public function removeContentCuration($contentCurationId)
    {
        return $this->post("content_curation/{$contentCurationId}/delete");
    }


    public function getSetReviews($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("/set_review", $filterArray);
    }

   public function findTargetGroupAndSendPush()
   {
       return $this->post("job/push/send");
   }

   public function getSetSegment($setId)
   {
       return $this->get("set_segment/{$setId}");
   }

    public function getSetSegmentBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("set_segment/by_set_ids", $filterArray);
    }

    public function updateOrCreateSetSegment($setId, $companyType, $sourceType, $timeliness)
    {
        $inputs = [
            'company_type' => $companyType,
            'source_type' => $sourceType,
            'timeliness' => $timeliness
        ];

        return $this->put("set_segment/{$setId}", $inputs);
    }

    public function createSetInterests($setId, $interestIds)
    {
        $inputs = [
            'set_id' => $setId,
            'interest_ids' => $interestIds
        ];

        return $this->post("set_interest/create_set_interests", $inputs);
    }

    public function deleteSetInterests($setId, $interestIds)
    {
        $inputs = [
            'set_id' => $setId,
            'interest_ids' => $interestIds
        ];

        return $this->post("set_interest/delete_set_interests", $inputs);
    }

    public function getSetInterestsBySetId($setId, $filterArray = [])
    {
        return $this->get("set_interest/set/{$setId}", $filterArray);
    }

    public function getSetInterestsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("set_interest/by_set_ids", $filterArray);
    }

    public function getSetInterestsByInterestIds($interestIds, $filterArray = [])
    {
        $filterArray['interest_ids'] = implode(',', $interestIds);
        return $this->get("set_interest/by_interest_ids", $filterArray);
    }

    public function getContentCharacteristics($filterArray = [])
    {
        return $this->get("content_characteristic", $filterArray);
    }

    public function createContentCharacteristic($name)
    {
        $inputs = [
            'name' => $name
        ];
        return $this->post("content_characteristic", $inputs);
    }

    public function deleteContentCharacteristic($contentCharacteristicId)
    {
        return $this->post("content_characteristic/{$contentCharacteristicId}/delete");
    }

    public function createSetContentCharacteristics($setId, $contentCharacteristicIds)
    {
        $inputs = [
            'set_id' => $setId,
            'content_characteristic_ids' => $contentCharacteristicIds
        ];

        return $this->post("set_content_characteristic/store_set_content_characteristics", $inputs);
    }

    public function deleteSetContentCharacteristics($setId, $contentCharacteristicIds)
    {
        $inputs = [
            'set_id' => $setId,
            'content_characteristic_ids' => $contentCharacteristicIds
        ];

        return $this->post("set_content_characteristic/delete_set_content_characteristics", $inputs);
    }

    public function getSetContentCharacteristics($setId)
    {
        return $this->get("set_content_characteristic/set/{$setId}");
    }

    public function createSetCareerTypes($setId, $careerTypes)
    {
        $inputs = [
            'set_id' => $setId,
            'career_types' => $careerTypes
        ];

        return $this->post("set_career_type/create_set_career_types", $inputs);
    }

    public function deleteSetCareerTypes($setId, $careerTypes)
    {
        $inputs = [
            'set_id' => $setId,
            'career_types' => $careerTypes
        ];

        return $this->post("set_career_type/delete_set_career_types", $inputs);
    }

    public function getSetCareerTypesBySetId($setId, $filterArray = [])
    {
        return $this->get("set_career_type/set/{$setId}", $filterArray);
    }

    public function getSetCareerTypesBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("set_career_type/by_set_ids", $filterArray);
    }

    public function createSetOccupationTypes($setId, $occupationTypes)
    {
        $inputs = [
            'set_id' => $setId,
            'occupation_types' => $occupationTypes
        ];

        return $this->post("set_occupation_type/create_set_occupation_types", $inputs);
    }

    public function deleteSetOccupationTypes($setId, $occupationTypes)
    {
        $inputs = [
            'set_id' => $setId,
            'occupation_types' => $occupationTypes
        ];

        return $this->post("set_occupation_type/delete_set_occupation_types", $inputs);
    }

    public function getSetOccupationTypesBySetId($setId, $filterArray = [])
    {
        return $this->get("set_occupation_type/set/{$setId}", $filterArray);
    }

    public function getSetOccupationTypesBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("set_occupation_type/by_set_ids", $filterArray);
    }

    public function createSetJobCategories($setId, $jobCategoryIds)
    {
        $inputs = [
            'set_id' => $setId,
            'job_category_ids' => $jobCategoryIds
        ];

        return $this->post("set_job_category/create_set_job_categories", $inputs);
    }

    public function deleteSetJobCategories($setId, $jobCategoryIds)
    {
        $inputs = [
            'set_id' => $setId,
            'job_category_ids' => $jobCategoryIds
        ];

        return $this->post("set_job_category/delete_set_job_categories", $inputs);
    }

    public function getSetJobCategoriesBySetId($setId, $filterArray = [])
    {
        return $this->get("set_job_category/set/{$setId}", $filterArray);
    }

    public function getUserSetProgressCountsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("user_set_progress/by_set_ids/count", $filterArray);
    }

    public function getUserSetProgressCountBySet($setId, $filterArray = [])
    {
        return $this->get("user_set_progress/set/{$setId}/count", $filterArray);
    }

    public function getGuides($page = 1, $limit = 20, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("guide", $filterArray);
    }

    public function getGuideCount($filterArray = [])
    {
        return $this->get("guide/count", $filterArray);
    }

    public function getGuide($guideId)
    {
        return $this->get("guide/{$guideId}");
    }

    public function getGuidesByIds($guideIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $guideIds);

        return $this->get("guide/by_ids", $filterArray);
    }

    public function createGuide($changerId, $title)
    {
        return $this->post("guide", [
            'changer_id' => $changerId,
            'title' => $title
        ]);
    }

    public function updateGuide($changerId, $guideId, $title, $mastheadImageUrl, $coverImageUrl)
    {
        return $this->put("guide/{$guideId}", [
            'changer_id' => $changerId,
            'title' => $title,
            'masthead_image_url' => $mastheadImageUrl,
            'cover_image_url' => $coverImageUrl
        ]);
    }

    public function updateGuideDescription($changerId, $guideId, $description)
    {
        return $this->put("guide/{$guideId}", [
            'changer_id' => $changerId,
            'description' => $description
        ]);
    }

    public function updateGuideIsActive($changerId, $guideId, $isActive)
    {
        return $this->put("guide/{$guideId}", [
            'changer_id' => $changerId,
            'is_active' => $isActive
        ]);
    }

    public function deleteGuide($changerId, $guideId)
    {
        return $this->post("guide/{$guideId}/delete", [
            'changer_id' => $changerId
        ]);
    }

    public function getGuideItemsByGuide($guideId, $filterArray = [])
    {
        return $this->get("guide_item/guide/{$guideId}", $filterArray);
    }

    public function getGuideItemsByGuideIds($guideIds, $filterArray = [])
    {
        $filterArray['guide_ids'] = implode(',', $guideIds);
        return $this->get("guide_item/by_guide_ids", $filterArray);
    }

    public function getGuideItemsCountByGuideIds($guideIds)
    {
        $filterArray = ['guide_ids' => implode(',', $guideIds)];

        return $this->get("/guide_item/by_guide_ids/count", $filterArray);
    }

    public function updateGuideItems($changerId, $guideId, $guideItemIds, $titles, $descriptions, $types, $itemIds, $randomStringIds)
    {
        return $this->put("guide_item/guide/{$guideId}", [
            'changer_id' => $changerId,
            'ids' => implode(',', $guideItemIds),
            'titles' => $titles,
            'descriptions' => $descriptions,
            'types' => implode(',', $types),
            'item_ids' => implode(',', $itemIds),
            'random_string_ids' => implode(',', $randomStringIds)
        ]);
    }

    public function getProjectSetsByProject($projectId, $filterArray = [])
    {
        return $this->get("project_set/project/{$projectId}", $filterArray);
    }

    public function getSetReviewCaches()
    {
        return $this->get("set_review_cache");
    }

    public function getSetReviewCache($setId)
    {
        return $this->get("set_review_cache/{$setId}");
    }

    public function refreshSetReviewCache()
    {
        return $this->post('set_review_cache_refresh');
    }

    public function getProjectSets($filterArray = [])
    {
        return $this->get("project_set", $filterArray);
    }

    public function getProjectSetsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("project_set", $filterArray);
    }

    public function getContentsCountBySetIds($setIds, $filterArray = [])
    {
        $filterArray['set_ids'] = implode(',', $setIds);

        return $this->get("content/by_set_ids/count", $filterArray);
    }

    public function getPageViewCount($pageType, $pageIds, $filterArray = [])
    {
        $filterArray['page_type'] = $pageType;
        $filterArray['page_ids'] = implode(',', $pageIds);

        return $this->get("page_view_count", $filterArray);
    }

    public function incrementPageViewCount($pageType, $pageId, $filterArray = [])
    {
        $filterArray['page_type'] = $pageType;
        $filterArray['page_id'] = $pageId;

        return $this->post("page_view_count", $filterArray);
    }

    public function getProjectDescriptions($filterArray = [])
    {
        return $this->get("project_description", $filterArray);
    }

    public function updateOrCreateProjectDescriptions($changerId, $projectId, $descriptionType, $description)
    {
        $inputs = [
            'changer_id' => $changerId,
            'project_id' => $projectId,
            'description_type' => $descriptionType,
            'description' => $description
        ];

        return $this->put("project_description", $inputs);
    }

    public function getUserContentProgressCountsGroupBySetAndUser($filterArray = [])
    {
        return $this->get("user_content_progress/count_group_by_set_and_user", $filterArray);
    }
}
