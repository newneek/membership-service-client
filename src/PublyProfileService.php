<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyProfileService extends BaseApiService
{
    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/api/";
    }

    /*
     * Career Related Functions
     */

    public function getCareersByProfileId($profileId)
    {
        return $this->get("careers/",
            [
                'profileId' => $profileId,
                'limit'=> 100,
                'page'=> 1,
                'sortBy' => 'displaySequence',
                'orderBy' => 'desc'
            ]);
    }

    public function addCareer($profileId, $type, $company, $title)
    {
        return $this->post("careers/",
            [
                'profileId' => $profileId,
                'type' => $type,
                'company' => $company,
                'title' => $title,
            ]);
    }

    public function updateCareer($careerId, $type, $company, $title)
    {
        return $this->put("careers/$careerId",
            [
                'type' => $type,
                'company' => $company,
                'title' => $title,
            ]);
    }

    public function deleteCareer($careerId)
    {
        return $this->delete("careers/$careerId");
    }
}
