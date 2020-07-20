<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyExtraService extends BaseApiService
{
    const REVIEW_LIKE_TYPE_LIKE = 1;
    const REVIEW_LIKE_TYPE_DISLIKE = 2;

    const SOCIAL_PROOF_TYPE_FACEBOOK = 1;
    const SOCIAL_PROOF_TYPE_TWITTER = 2;
    const SOCIAL_PROOF_TYPE_INSTAGRAM = 3;
    const SOCIAL_PROOF_TYPE_MAX = 4;

    const MAXIMUM_SHAREABLE_COUNT = 5;

    const FEATURED_BANNER_ITEM_TYPE_SET = 1;
    const FEATURED_BANNER_ITEM_TYPE_MANUAL = 2;
    const FEATURED_BANNER_ITEM_TYPE_TO_BE_PUBLISHED_SETS = 3;

    const COMPANY_TYPE_SMALL_AND_MEDIUM_SIZED_COMPANY = 1;
    const COMPANY_TYPE_LARGE_COMPANY = 2;
    const COMPANY_TYPE_START_UP = 3;
    const COMPANY_TYPE_STUDENT = 4;
    const COMPANY_TYPE_ETC = 5;
    const COMPANY_TYPE_FREELANCER = 6;

    const MANAGEMENT_LEVEL_HANDS_ON_WORKER = 1;
    const MANAGEMENT_LEVEL_MANAGER = 2;
    const MANAGEMENT_LEVEL_DECISION_MAKER = 3;

    const STRING_COMPANY_TYPE = [
        PublyExtraService::COMPANY_TYPE_SMALL_AND_MEDIUM_SIZED_COMPANY => 'small-and-medium-sized-company',
        PublyExtraService::COMPANY_TYPE_LARGE_COMPANY => 'large-company',
        PublyExtraService::COMPANY_TYPE_START_UP => 'start-up',
        PublyExtraService::COMPANY_TYPE_STUDENT => 'student',
        PublyExtraService::COMPANY_TYPE_ETC => 'etc',
        PublyExtraService::COMPANY_TYPE_FREELANCER => 'freelancer',
    ];

    const STRING_MANAGEMENT_LEVEL = [
        PublyExtraService::MANAGEMENT_LEVEL_HANDS_ON_WORKER => 'hands-on-worker',
        PublyExtraService::MANAGEMENT_LEVEL_MANAGER => 'manager',
        PublyExtraService::MANAGEMENT_LEVEL_DECISION_MAKER => 'decision-maker'
    ];

    const COMPLETE_READING_MESSAGE_TYPE_SET = 1;
    const COMPLETE_READING_MESSAGE_TYPE_CONTENT = 2;

    const STRING_COMPLETE_READING_MESSAGE_TYPE = [
        PublyExtraService::COMPLETE_READING_MESSAGE_TYPE_SET => '웹북/아티클',
        PublyExtraService::COMPLETE_READING_MESSAGE_TYPE_CONTENT => '챕터',
    ];

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function createSocialProof($changerId, $type, $order, $link)
    {
        $inputs = [
            'changer_id' => $changerId,
            'type' => $type,
            'order' => $order,
            'link' => $link
        ];

        return $this->put("social_proof", $inputs);
    }

    public function getSocialProofs($page = 1,
                                    $limit = 10,
                                    $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("social_proof", $filterArray);
    }

    public function getSocialProof($socialProofId)
    {
        return $this->get("social_proof/{$socialProofId}");
    }

    public function updateSocialProof($changerId, $socialProofId, $type, $order, $link)
    {
        $inputs = [
            'changer_id' => $changerId,
            'type' => $type,
            'order' => $order,
            'link' => $link
        ];

        return $this->post("social_proof/{$socialProofId}", $inputs);
    }

    public function deleteSocialProof($changerId, $socialProofId)
    {
        return $this->post("social_proof/{$socialProofId}/delete",
            ['changer_id' => $changerId]
        );
    }


    public function createMeeting($changerId,
                                  $eventId,
                                  $place,
                                  $placeLink,
                                  $startAt,
                                  $finishAt)
    {
        $inputs = [
            'changer_id' => $changerId,
            'event_id' => $eventId,
            'place' => $place,
            'place_link' => $placeLink,
            'start_at' => $startAt,
            'finish_at' => $finishAt
        ];

        return $this->put("meeting", $inputs);
    }

    public function getMeetingsByEventIds($eventIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $eventIds);
        return $this->get("meeting/event", $filterArray);
    }

    public function getMeeting($meetingId)
    {
        return $this->get("meeting/{$meetingId}");
    }

    public function updateMeeting($changerId,
                                  $meetingId,
                                  $eventId,
                                  $place,
                                  $placeLink,
                                  $startAt,
                                  $finishAt)
    {
        $inputs = [
            'changer_id' => $changerId,
            'event_id' => $eventId,
            'place' => $place,
            'place_link' => $placeLink,
            'start_at' => $startAt,
            'finish_at' => $finishAt
        ];

        return $this->post("meeting/{$meetingId}", $inputs);
    }

    public function deleteMeetingByEvent($changerId, $eventId)
    {
        return $this->post("meeting/delete/event/{$eventId}",
            ['changer_id' => $changerId]
        );
    }

    public function createEventDisplay($changerId,
                                       $eventId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'event_id' => $eventId
        ];

        return $this->put("event_display", $inputs);
    }

    public function getEventDisplays($filterArray = [])
    {
        return $this->get("event_display/", $filterArray);
    }

    public function getEventDisplaysByEvents($eventIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $eventIds);
        return $this->get("event_display/event", $filterArray);
    }

    public function updateEventDisplayOrder($changerId, $eventDisplayIds)
    {
        return $this->post("event_display/order",
            ['changer_id' => $changerId,
                'ids' => implode(',', $eventDisplayIds)]);
    }

    public function deleteEventDisplay($changerId, $eventDisplayId)
    {
        return $this->post("event_display/{$eventDisplayId}/delete",
            ['changer_id' => $changerId]
        );
    }

    public function addSearchableObject($indexName, $objectId, $record)
    {
        $inputs = [
            'index_name' => $indexName,
            'object_id' => $objectId,
            'record' => $record
        ];

        return $this->put("/searchable_object", $inputs);
    }

    public function updateSearchableObject($indexName, $objectId, $record)
    {
        $inputs = [
            'index_name' => $indexName,
            'object_id' => $objectId,
            'record' => $record
        ];

        return $this->post("/searchable_object", $inputs);
    }

    public function deleteSearchableObject($indexName, $objectId)
    {
        $inputs = [
            'index_name' => $indexName,
            'object_id' => $objectId
        ];

        return $this->post("/searchable_object/delete", $inputs);
    }

    public function deleteSearchableObjectByFilter($indexName, $filter)
    {
        $inputs = [
            'index_name' => $indexName,
            'filter' => json_encode($filter)
        ];

        return $this->post("/searchable_object/delete", $inputs);
    }

    public function createEventGroup($changerId,
                                     $title,
                                     $order)
    {
        $inputs = [
            'changer_id' => $changerId,
            'title' => $title,
            'order' => $order
        ];

        return $this->post("event_group", $inputs);
    }

    public function getEventGroups($filterArray = [])
    {
        return $this->get("event_group", $filterArray);
    }

    public function getEventGroup($eventGroupId)
    {
        return $this->get("event_group/{$eventGroupId}");
    }

    public function updateEventGroup($changerId,
                                     $eventGroupId,
                                     $title,
                                     $imageUrl,
                                     $isShow)
    {
        $inputs = [
            'changer_id' => $changerId,
            'title' => $title,
            'image_url' => $imageUrl,
            'is_show' => $isShow
        ];
        return $this->put("event_group/{$eventGroupId}", $inputs);
    }

    public function updateEventGroupOrder($changerId, $eventGroupIds)
    {
        return $this->put("event_group/order",
            ['changer_id' => $changerId,
                'ids' => implode(',', $eventGroupIds)]);
    }

    public function deleteEventGroup($changerId, $eventGroupId)
    {
        return $this->post("event_group/{$eventGroupId}/delete",
            ['changer_id' => $changerId]
        );
    }

    public function createEventGroupSet($changerId, $eventGroupId, $setId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'event_group_id' => $eventGroupId,
            'set_id' => $setId
        ];

        return $this->post("event_group_set", $inputs);
    }

    public function deleteEventGroupSet($changerId, $eventGroupId, $setId)
    {
        $inputs = [
            'changer_id' => $changerId
        ];

        return $this->post("/event_group_set/event_group/{$eventGroupId}/set/{$setId}/delete", $inputs);
    }


    public function getSharedContentByUserAndContent($userId, $contentId)
    {
        return $this->get("/shared_content/user/{$userId}/content/{$contentId}");
    }

    public function getSharedContentByCode($code)
    {
        return $this->get("/shared_content/code/{$code}");
    }

    public function getSharedContentByContentAndCode($contentId, $code)
    {
        return $this->get("/shared_content/content/{$contentId}/code/{$code}");
    }

    public function createSharedContentByUserAndContent($userId, $contentId)
    {
        $inputs = [
            'user_id' => $userId,
            'content_id' => $contentId
        ];

        return $this->post("/shared_content", $inputs);
    }

    public function getContentShareCount($userId)
    {
        return $this->get("/content_share_count/user/{$userId}");
    }

    public function updateOrCreateContentShareCount($userId, $remainingCount)
    {
        $inputs = [
            'user_id' => $userId,
            'remaining_count' => $remainingCount
        ];

        return $this->put("/content_share_count/user/{$userId}", $inputs);
    }

    public function getEventSetsByEventId($eventId)
    {
        return $this->get("/event_set/event/{$eventId}");
    }

    public function getEventSetsBySetId($setId)
    {
        return $this->get("/event_set/set/{$setId}");
    }

    public function getEventSetsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("/event_set/set_ids", $filterArray);
    }

    public function createEventSet($changerId, $eventId, $setId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'event_id' => $eventId,
            'set_id' => $setId
        ];

        return $this->post("event_set", $inputs);
    }

    public function deleteEventSet($changerId, $eventId, $setId)
    {
        $inputs = [
            'changer_id' => $changerId
        ];

        return $this->post("/event_set/event/{$eventId}/set/{$setId}/delete", $inputs);
    }

    public function updateOrCreateContentShareCountByUser($userId, $remainingCount)
    {
        $inputs = [
            'remaining_count' => $remainingCount
        ];
        return $this->put("content_share_count/user/{$userId}", $inputs);
    }

    public function rechargeContentShareCountByUser($userId, $rechargingCount = self::MAXIMUM_SHAREABLE_COUNT)
    {
        return $this->updateOrCreateContentShareCountByUser($userId, $rechargingCount);
    }

    public function createMarketingSetReview($changerId, $setId, $setReviewId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'set_id' => $setId,
            'set_review_id' => $setReviewId
        ];

        return $this->post("marketing_set_review", $inputs);
    }

    public function getMarketingSetReviewsBySetId($setId)
    {
        return $this->get("marketing_set_review/set/{$setId}");
    }

    public function getMarketingSetReviewsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("marketing_set_review/by_set_ids", $filterArray);
    }

    public function deleteMarketingSetReview($changerId, $marketingSetReviewId)
    {
        return $this->post("marketing_set_review/{$marketingSetReviewId}/delete",
            ['changer_id' => $changerId]);
    }

    public function getShowAllContents($page = 1,
                                       $limit = 10,
                                       $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("show_all_content", $filterArray);
    }

    //deprecated
    public function getShowAllContentByContent($contentId)
    {
        return $this->get("show_all_content/content/{$contentId}");
    }

    //deprecated
    public function getShowAllContentByContents($contentIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $contentIds);
        return $this->get("show_all_content/content_ids", $filterArray);
    }

    public function getShowAllContentByContent2($contentId)
    {
        return $this->get("show_all_content/show/content/{$contentId}");
    }

    public function getShowAllContentByContents2($contentIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $contentIds);
        return $this->get("show_all_content/content_ids_2", $filterArray);
    }

    public function createShowAllContent($changerId, $contentId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'content_id' => $contentId,
        ];
        return $this->put("show_all_content", $inputs);
    }

    public function deleteShowAllContent($changerId, $showAllContentId)
    {
        return $this->post("show_all_content/{$showAllContentId}/delete",
            ['changer_id' => $changerId]);
    }

    public function calculateSetSimilarityCoefficient()
    {
        return $this->post("set_similarity_coefficient");
    }

    public function getSetSimilarityCoefficientBySetId($setId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("set_similarity_coefficient/set/{$setId}", $filterArray);
    }

    public function createRankingByType($changerId, $type, $filterArray = [])
    {
        if (!$type) {
            $type = 1;
        }
        $filterArray['changer_id'] = $changerId;
        return $this->post("ranking/type/{$type}/create", $filterArray);
    }

    public function getLatestRankings($filterArray = [])
    {
        return $this->get("ranking/latest", $filterArray);
    }

    public function getReviewLikesBySetAndUser($setId, $userId, $filterArray = [])
    {
        return $this->get("review_like/set/{$setId}/user/{$userId}", $filterArray);
    }

    public function updateOrCreateReviewLikeByReviewAndUser($changerId, $reviewId, $userId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("review_like/review_id/{$reviewId}/user/{$userId}", $inputs);
    }

    public function deleteReviewLikeByReviewAndUser($changerId, $reviewId, $userId)
    {
        $inputs = [ 'changer_id' => $changerId ];
        return $this->put("review_like/review_id/{$reviewId}/user/{$userId}/delete", $inputs);
    }

    public function updateAllSetReviewScores($changerId)
    {
        $inputs = [ 'changer_id' => $changerId ];
        return $this->put("review_score/all", $inputs);
    }

    public function getReviewLikeCountsOfReviewBySet($setId, $filterArray = [])
    {
        return $this->get("review_like/set/{$setId}/counts", $filterArray);
    }

    public function getReviewScoreBySet($setId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

       return $this->get("review_score/set/{$setId}", $filterArray);
    }

    public function createOnboardingProcess($userId)
    {
        $inputs = ['user_id' => $userId];

        return $this->post("onboarding_process", $inputs);
    }

    public function getOnboardingProcessByUser($userId, $filterArray = [])
    {
        return $this->get("onboarding_process/user/{$userId}", $filterArray);
    }

    public function updateOrCreateOnboardingProcessByUser($userId, $action)
    {
        $inputs = [
            'action' => $action
        ];

        return $this->put("onboarding_process/user/{$userId}", $inputs);
    }

    public function getFeaturedBannerItems($filterArray = [])
    {
        return $this->get("featured_banner_item", $filterArray);
    }

    public function getFeaturedBannerItem($featuredBannerItemId)
    {
        return $this->get("featured_banner_item/{$featuredBannerItemId}");
    }

    public function createFeaturedBannerItem(
        $changerId,
        $note,
        $type,
        $setId,
        $htmlPc,
        $htmlMobile
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'note' => $note,
            'type' => $type,
            'set_id' => $setId,
            'html_pc' => $htmlPc,
            'html_mobile' => $htmlMobile
        ];

        return $this->post("featured_banner_item", $inputs);
    }

    public function updateFeaturedBannerItem(
        $changerId,
        $featuredBannerItemId,
        $note,
        $type,
        $setId,
        $htmlPc,
        $htmlMobile
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'note' => $note,
            'type' => $type,
            'set_id' => $setId,
            'html_pc' => $htmlPc,
            'html_mobile' => $htmlMobile
        ];

        return $this->put("featured_banner_item/{$featuredBannerItemId}", $inputs);
    }

    public function updateFeaturedBannerItemOrder($changerId, $featuredBannerItemIds)
    {
        return $this->put("featured_banner_item/update_order", [
            'changer_id' => $changerId,
            'ids' => implode(',', $featuredBannerItemIds)
        ]);
    }

    public function deleteFeaturedBannerItem($changerId, $featurdBannerItemId)
    {
        return $this->post("featured_banner_item/{$featurdBannerItemId}/delete", [
            'changer_id' => $changerId
        ]);
    }

    public function createHighlight(
        $changerId,
        $userId,
        $setId,
        $contentId,
        $sectionIndex,
        $paragraphIndex,
        $isHighlighted,
        $phrase,
        $order,
        $position,
        $note
    ) {
        $inputs =  [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'set_id' => $setId,
            'content_id' => $contentId,
            'section_index' => $sectionIndex,
            'paragraph_index' => $paragraphIndex,
            'is_highlighted' => $isHighlighted,
            'phrase' => $phrase,
            'order' => $order,
            'position' => $position,
            'note' => $note
        ];

        return $this->post("highlight", $inputs);
    }

    public function getHighlightsByUser($userId, $filterArray = [])
    {
        return $this->get("highlight/user/{$userId}", $filterArray);
    }

    public function getHighlightsByUserAndContent($userId, $contentId, $filterArray = [])
    {
        return $this->get("highlight/user/{$userId}/content/{$contentId}", $filterArray);
    }

    public function getHighlightsByUserAndSet($userId, $setId, $filterArray = [])
    {
        return $this->get("highlight/user/{$userId}/set/{$setId}", $filterArray);
    }

    public function updateHighlightNote($changerId, $highlightId, $note)
    {
        $inputs = [
            'changer_id' => $changerId,
            'note' => $note
        ];

        return $this->put("highlight/{$highlightId}", $inputs);
    }

    public function updateIsHighlighted($changerId, $highlightId, $isHighlighted)
    {
        $inputs = [
            'changer_id' => $changerId,
            'is_highlighted' => $isHighlighted
        ];

        return $this->put("highlight/{$highlightId}", $inputs);
    }

    public function deleteHighlight($changerId, $highlightId)
    {
        $inputs = [
            'changer_id' => $changerId
        ];

        return $this->post("highlight/{$highlightId}/delete", $inputs);
    }

    public function getReviewScoreCountsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("review_score/counts/by_set_ids", $filterArray);
    }

    public function refreshFeedDisplays()
    {
        return $this->post("feed_display/refresh");
    }

    public function createInterest($name, $categoryId)
    {
        $inputs = [
            'name' => $name,
            'category_id' => $categoryId
        ];

        return $this->post("interest", $inputs);
    }

    public function updateInterest($interestId, $name, $categoryId)
    {
        $inputs = [
            'name' => $name,
            'category_id' => $categoryId
        ];

        return $this->put("interest/{$interestId}/update", $inputs);
    }

    public function updateInterestDisplaySequence($interestIds)
    {
        return $this->put("interest/update_display_sequence", [
            'ids' => implode(',', $interestIds)
        ]);
    }

    public function deleteInterest($interest)
    {
        return $this->post("interest/{$interest}/delete");
    }

    public function getInterests($filterArray = [])
    {
        return $this->get("interest", $filterArray);
    }

    public function getInterest($interestId)
    {
        return $this->get("interest/{$interestId}");
    }

    public function getInterestsByCategoryId($categoryId)
    {
        return $this->get("interest/category/{$categoryId}");
    }

    public function getInterestsByIds($interestIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $interestIds);

        return $this->get("interest/by_ids", $filterArray);
    }

    public function createJobCategory($name)
    {
        $inputs = [
            'name' => $name
        ];

        return $this->post("job_category", $inputs);
    }

    public function deleteJobCategory($jobCategoryId)
    {
        return $this->post("job_category/{$jobCategoryId}/delete");
    }

    public function getJobCategories($filterArray = [])
    {
        return $this->get("job_category", $filterArray);
    }

    public function getJobCategory($jobCategoryId)
    {
        return $this->get("job_category/{$jobCategoryId}");
    }

    public function updateJobCategoryDisplaySequence($jobCategoryIds)
    {
        return $this->put("job_category/update_display_sequence", [
            'ids' => implode(',', $jobCategoryIds)
        ]);
    }

    public function getUserSegment($userId)
    {
        return $this->get("user_segment/user/{$userId}");
    }

    public function getUserSegmentsByUserIds($userIds, $filterArray = [])
    {
        $filterArray['user_ids'] = implode(',', $userIds);
        return $this->get("user_segment/by_user_ids", $filterArray);
    }

    public function createUserSegment($changerId, $userId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId
        ];

        return $this->post('user_segment', $inputs);
    }

    public function updateUserSegment(
        $changerId,
        $userId,
        $companyType,
        $managementLevel,
        $careerYear,
        $jobCategory)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'company_type' => $companyType,
            'management_level' => $managementLevel,
            'career_year' => $careerYear,
            'job_category_id' => $jobCategory
        ];

        return $this->put('user_segment', $inputs);
    }

    public function updateUserSegmentCompanyType($changerId, $userId, $companyType)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'company_type' => $companyType
        ];

        return $this->put('user_segment', $inputs);
    }

    public function updateUserSegmentCareer($changerId, $userId, $managementLevel, $careerYear)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'management_level' => $managementLevel,
            'career_year' => $careerYear
        ];

        return $this->put('user_segment', $inputs);
    }

    public function updateUserSegmentJobCategory($changerId, $userId, $jobCategoryId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'job_category_id' => $jobCategoryId
        ];

        return $this->put('user_segment', $inputs);
    }

    public function getUserInterests($userId)
    {
        return $this->get("user_interest/user/{$userId}");
    }

    public function getUserInterestsByUserIds($userIds, $filterArray = [])
    {
        $filterArray['user_ids'] = implode(',', $userIds);
        return $this->get("user_interest/by_user_ids", $filterArray);
    }

    public function createUserInterests($changerId, $userId, $interestIds = [])
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'interest_ids' => $interestIds
        ];

        return $this->post('user_interest', $inputs);
    }

    public function deleteUserInterests($changerId, $userId, $interestIds)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'interest_ids' => $interestIds
        ];

        return $this->post('user_interest/delete', $inputs);
    }

    public function refreshFeedForAllActiveUsers()
    {
        return $this->post("/user_feed/refresh");
    }

    public function getUserFeedStatus($userId)
    {
        return $this->get("user_feed_status/$userId");
    }

    public function updateOrCreateUserFeedStatus($userId)
    {
        return $this->post("user_feed_status/$userId");
    }

    public function getUserFeedByUser($userId)
    {
        return $this->get("user_feed/user/$userId");
    }

    public function getDailyRecommendations($filterArray = [])
    {
        return $this->get("daily_recommendation", $filterArray);
    }

    public function getDailyRecommendationsBySegmentTypeAndDate($segmentType, $date, $filterArray = [])
    {
        return $this->get("daily_recommendation/segment_type/{$segmentType}/date/{$date}", $filterArray);
    }

    public function createDailyRecommendations($date, $segmentType, $contentIds)
    {
        $inputs = [
            'date' => $date,
            'segment_type' => $segmentType,
            'content_ids' => $contentIds
        ];

        return $this->post("daily_recommendation/store_daily_recommendations", $inputs);
    }

    public function deleteDailyRecommendation($dailyRecommendationId)
    {
        return $this->post("daily_recommendation/{$dailyRecommendationId}/delete");
    }

    public function createCompleteReadingMessage($message, $type, $source, $contentReadingCount)
    {
        $inputs = [
            'message' => $message,
            'type' => $type,
            'source' => $source,
            'content_reading_count' => $contentReadingCount
        ];

        return $this->post("complete_reading_message", $inputs);
    }

    public function getCompleteReadingMessages($filterArray = [])
    {
        return $this->get("complete_reading_message", $filterArray);
    }

    public function getCompleteReadingMessage($completeReadingMessageId)
    {
        return $this->get("complete_reading_message/{$completeReadingMessageId}");
    }

    public function deleteCompleteReadingMessage($completeReadingMessageId)
    {
        return $this->post("complete_reading_message/{$completeReadingMessageId}/delete");
    }

    public function updateCompleteReadingMessage($completeReadingMessageId, $message, $type, $source, $contentReadingCount)
    {
        $inputs = [
            'message' => $message,
            'type' => $type,
            'source' => $source,
            'content_reading_count' => $contentReadingCount
        ];

        return $this->put("complete_reading_message/{$completeReadingMessageId}/update", $inputs);
    }

    public function getCompleteReadingMessageByType($type, $filterArray = [])
    {
        return $this->get("complete_reading_message/type/{$type}/show", $filterArray);
    }

    public function getUserFeedScoreByUserId($userId, $filterArray = [])
    {
        return $this->get("user_feed_score/user/{$userId}", $filterArray);
    }

    public function createUserFeedFactor($userId, $setId, $factorId)
    {
        $inputs = [
            'user_id' => $userId,
            'set_id' => $setId,
            'factor_id' => $factorId
        ];

        return $this->post("user_feed_factor", $inputs);
    }

    public function getWriterSetReviewRepliesBySetReviewId($setReviewId, $filterArray = [])
    {
        return $this->get("/writer_set_review_reply/set_review/{$setReviewId}", $filterArray);
    }

    public function getWriterSetReviewRepliesBySet($setId, $filterArray = [])
    {
        return $this->get("/writer_set_review_reply/set/{$setId}", $filterArray);
    }

    public function createWriterSetReviewReply($setId, $setReviewId, $profileId, $comment)
    {
        $inputs = [
            'set_id' => $setId,
            'set_review_id' => $setReviewId,
            'profile_id' => $profileId,
            'comment' => $comment
        ];

        return $this->post("/writer_set_review_reply", $inputs);
    }

    public function updateWriterSetReviewReply($writerSetReviewReplyId, $profileId, $comment)
    {
        $inputs = [
            'profile_id' => $profileId,
            'comment' => $comment
        ];

        return $this->put("/writer_set_review_reply/{$writerSetReviewReplyId}/update", $inputs);
    }

    public function deleteWriterSetReviewReply($writerSetReviewReplyId)
    {
        return $this->post("/writer_set_review_reply/{$writerSetReviewReplyId}/delete");
    }

    public function getUserVideoContentProgressByUserAndContent($userId, $contentId)
    {
        return $this->get("/user_video_content_progress/user/{$userId}/content/{$contentId}");
    }

    public function updateOrStoreUserVideoContentProgress($userId, $setId, $contentId, $lastPlayTime, $duration, $action = 'update')
    {
        $inputs = [
            'last_play_time' => $lastPlayTime,
            'action' => $action,
            'duration' => $duration
        ];

        return $this->put("/user_video_content_progress/user/{$userId}/set/{$setId}/content/{$contentId}", $inputs);
    }
}
