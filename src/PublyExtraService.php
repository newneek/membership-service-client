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
            [ 'changer_id' => $changerId ]
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
            [ 'changer_id' => $changerId ]
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

    public function updateEventDisplayOrder($changerId, $eventDisplayIds)
    {
        return $this->post("event_display/order",
            [ 'changer_id' => $changerId,
                'ids' => implode(',', $eventDisplayIds) ]);
    }

    public function deleteEventDisplay($changerId, $eventDisplayId)
    {
        return $this->post("event_display/{$eventDisplayId}/delete",
            [ 'changer_id' => $changerId ]
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

        return $this->post("event_group",$inputs);
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
            [ 'changer_id' => $changerId,
                'ids' => implode(',', $eventGroupIds) ]);
    }

    public function deleteEventGroup($changerId, $eventGroupId)
    {
        return $this->post("event_group/{$eventGroupId}/delete",
            [ 'changer_id' => $changerId ]
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
        return $this->get("/event_set/{$eventId}");
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
}