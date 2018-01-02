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


    public function createSetEventDisplay($changerId,
                                          $setId,
                                          $order)
    {
        $inputs = [
            'changer_id' => $changerId,
            'set_id' => $setId,
            'order' => $order
        ];

        return $this->put("set_event_display", $inputs);
    }

    public function getSetEventDisplays($filterArray = [])
    {
        return $this->get("set_event_display/", $filterArray);
    }

    public function getSetEventDisplay($setEventDisplayId)
    {
        return $this->get("set_event_display/{$setEventDisplayId}");
    }

    public function updateSetEventDisplay($changerId,
                                          $setEventDisplayId,
                                          $setId,
                                          $order)
    {
        $inputs = [
            'changer_id' => $changerId,
            'set_id' => $setId,
            'order' => $order
        ];

        return $this->post("set_event_display/{$setEventDisplayId}", $inputs);
    }

    public function updateSetEventDisplayOrder($changerId, $setEventDisplayIds)
    {
        return $this->post("set_event_display/order",
            [ 'changer_id' => $changerId,
                'ids' => implode(',', $setEventDisplayIds) ]);
    }

    public function deleteSetEventDisplay($changerId, $setEventDisplayId)
    {
        return $this->post("set_event_display/{$setEventDisplayId}/delete/",
            [ 'changer_id' => $changerId ]
        );
    }
}