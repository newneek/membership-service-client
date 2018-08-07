<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyExtraService extends BaseApiService
{
    const SOCIAL_PROOF_TYPE_FACEBOOK = 1;
    const SOCIAL_PROOF_TYPE_TWITTER = 2;
    const SOCIAL_PROOF_TYPE_INSTAGRAM = 3;
    const SOCIAL_PROOF_TYPE_MAX = 4;

    const MAXIMUM_SHAREABLE_COUNT = 5;

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

    public function deleteSearchableObjectByQuery($indexName, $query)
    {
        $inputs = [
            'index_name' => $indexName,
            'query' => $query
        ];

        return $this->post("/searchable_object/deleteByQuery", $inputs);
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

    public function getShowAllContentByContent($contentId)
    {
        return $this->get("show_all_content/content/{$contentId}");
    }

    public function getShowAllContentByContents($contentIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $contentIds);
        return $this->get("show_all_content/content_ids", $filterArray);
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

    public function getSetSimilarityCoefficientBySetId($setId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("set_similarity_coefficient/set/{$setId}", $filterArray);
    }

    public function createRankingByType($changerId, $type, $filterArray)
    {
        $filterArray['changer_id'] = $changerId;
        return $this->post("/ranking/type/{$type}/create", $filterArray);
    }
    
    public function getLatestRankings($filterArray = [])
    {
        return $this->get("/ranking/latest", $filterArray);
    }
}