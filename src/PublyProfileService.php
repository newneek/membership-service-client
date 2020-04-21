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

    public function getProfile($profileId)
    {
        return $this->get("profiles/",
            [
                'id' => $profileId
            ]);
    }

    public function getProfiles($page = 1, $limit = 100, $sortBy = 'p.id', $orderBy = 'desc', $filterArray = [])
    {
        $filterArray['limit'] = $limit;
        $filterArray['page'] = $page;
        $filterArray['sortBy'] = $sortBy;
        $filterArray['orderBy'] = $orderBy;
        return $this->get("profiles/", $filterArray);
    }

    public function getProfilesByProfileIds($profileIds, $page = 1, $limit = 100, $sortBy = 'p.id', $orderBy = 'desc')
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

    public function getProfilesByUserId($userId, $page = 1, $limit = 100, $sortBy = 'p.id', $orderBy = 'desc')
    {
        return $this->get("profiles/",
            [
                'userId' => $userId,
                'limit'=> $limit,
                'page'=> $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]);
    }

    public function createProfile($name)
    {
        return $this->post("profiles/", [
            'name' => $name
        ]);
    }

    public function updateProfile($profileId, $name, $imageUrl, $headline, $description, $longDescription, $adminNote, $links, $hashtags)
    {
        return $this->put("profiles/$profileId", [
            'name' => $name,
            'imageUrl' => $imageUrl,
            'headline' => $headline,
            'adminNote' => $adminNote,
            'description' => $description,
            'links' => $links,
            'hashtags' => $hashtags,
            'longDescription' => $longDescription
        ]);
    }

    public function getTotalProfileCount()
    {
        return $this->get("profiles/count");
    }

    public function createProfileUser($profileId, $userId)
    {
        return $this->post("profile-users/", [
            'profileId' => $profileId,
            'userId' => $userId
        ]);
    }

    public function deleteProfileUser($profileUserId)
    {
        return $this->delete("profile-users/$profileUserId");
    }

    public function getProfileUsersByProfileId($profileId, $page = 1, $limit = 100, $sortBy = 'id', $orderBy = 'desc')
    {
        return $this->get("profile-users/",
            [
                'profileId' => $profileId,
                'limit'=> $limit,
                'page'=> $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]);
    }
}
