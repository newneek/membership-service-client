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
                'orderBy' => 'asc'
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

    public function updateOrder($careerId, $order)
    {
        return $this->put("careers/$careerId",
            [
                'displaySequence' => $order,
            ]);
    }

    public function getProfilesByProfileIds($profileIds, $page = 1, $limit = 100, $sortBy = 'p.id', $orderBy = 'asc')
    {
        $profileIds = implode(',', $profileIds);

        return $this->get("profiles/",
            [
                'id' => $profileIds,
                'limit'=> $limit,
                'page'=> $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]);
    }
}
