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

    public function createSocialProof($changerId, $type, $link)
    {
        $inputs = [
            'changer_id' => $changerId,
            'type' => $type,
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
        return $this->post("social_proof/{$socialProofId}",
            [ 'changer_id' => $changerId ]
        );
    }
}