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
        return $this->get(
            "careers/",
            [
                'profileId' => $profileId,
                'limit' => 100,
                'page' => 1,
                'sortBy' => 'displaySequence',
                'orderBy' => 'asc'
            ]
        );
    }

    public function getCareersV2(array $params)
    {
        $profileIds = implode(",", $params['profileIds']);

        return $this->get(
            "careers/",
            [
                'profileIds' => $profileIds,
                'page' => $params['page'],
                'limit' => $params['limit'],
                'version' => 2,
            ]
        );
    }

    public function addCareerV2(array $params)
    {
        return $this->post("careers?version=2", [
            'profileId' => (int) $params['profileId'],
            'company' => $params['company'],
            'title' => $params['title'],
            'isCurrent' => $params['isCurrent'],
            'startDate' => $params['startDate'] ?? null,
            'endDate' => $params['endDate'] ?? null,
            'description' => $params['description'] ?? null,
            'url' => $params['url'] ?? null,
        ]);
    }

    public function updateCareerV2(int $careerId, array $params)
    {
        return $this->put("careers/{$careerId}?version=2", [
            'profileId' => (int) $params['profileId'],
            'company' => $params['company'],
            'title' => $params['title'],
            'isCurrent' => $params['isCurrent'],
            'startDate' => $params['startDate'] ?? null,
            'endDate' => $params['endDate'] ?? null,
            'description' => $params['description'] ?? null,
            'url' => $params['url'] ?? null,
        ]);
    }

    public function addCareer($profileId, $type, $company, $title)
    {
        return $this->post(
            "careers/",
            [
                'profileId' => $profileId,
                'type' => $type,
                'company' => $company,
                'title' => $title,
            ]
        );
    }

    public function updateCareer($careerId, $type, $company, $title)
    {
        return $this->put(
            "careers/$careerId",
            [
                'type' => $type,
                'company' => $company,
                'title' => $title,
            ]
        );
    }

    public function deleteCareer($careerId)
    {
        return $this->delete("careers/$careerId");
    }

    public function getEducations(array $params)
    {
        $profileIds = implode(",", $params['profileIds']);

        return $this->get(
            "educations/",
            [
                'profileIds' => $profileIds,
                'page' => $params['page'],
                'limit' => $params['limit'],
            ]
        );
    }

    public function addEducation(array $params)
    {
        return $this->post("educations", [
            'profileId' => (int) $params['profileId'],
            'institute' => $params['institute'],
            'major' => $params['major'],
            'isCurrent' => $params['isCurrent'],
            'startDate' => $params['startDate'] ?? null,
            'endDate' => $params['endDate'] ?? null,
            'description' => $params['description'] ?? null,
            'url' => $params['url'] ?? null,
        ]);
    }

    public function updateEducation(int $educationId, array $params)
    {
        return $this->put("educations/{$educationId}", [
            'profileId' => (int) $params['profileId'],
            'institute' => $params['institute'],
            'major' => $params['major'],
            'isCurrent' => $params['isCurrent'],
            'startDate' => $params['startDate'] ?? null,
            'endDate' => $params['endDate'] ?? null,
            'description' => $params['description'] ?? null,
            'url' => $params['url'] ?? null,
        ]);
    }

    public function deleteEducation(int $educationId)
    {
        return $this->delete("educations/{$educationId}");
    }

    public function updateOrder($careerId, $order)
    {
        return $this->put(
            "careers/$careerId",
            [
                'displaySequence' => $order,
            ]
        );
    }

    public function getProfile($profileId)
    {
        return $this->get(
            "profiles/",
            [
                'id' => $profileId
            ]
        );
    }

    public function getProfiles($page = 1, $limit = 100, $sortBy = 'id', $orderBy = 'desc', $filterArray = [])
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

        return $this->get(
            "profiles/",
            [
                'id' => $profileIds,
                'limit' => $limit,
                'page' => $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]
        );
    }

    public function getProfilesByUserId($userId, $page = 1, $limit = 100, $sortBy = 'p.id', $orderBy = 'desc')
    {
        return $this->get(
            "profiles/",
            [
                'userId' => $userId,
                'limit' => $limit,
                'page' => $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]
        );
    }

    public function getProfilesByUserIds($userIds, $page = 1, $limit = 100, $sortBy = 'p.id', $orderBy = 'desc')
    {
        $userIds = implode(',', $userIds);

        return $this->get(
            "profiles/",
            [
                'userId' => $userIds,
                'limit' => $limit,
                'page' => $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]
        );
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

    public function updateProfilePartial($profileId, $name, $imageUrl, $headline, $description, $longDescription, $adminNote, $links, $hashtags)
    {
        return $this->patch("profiles/$profileId", [
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

    public function updateProfileName($profileId, $name)
    {
        return $this->put("profiles/{$profileId}", [
            'name' => $name
        ]);
    }

    public function deleteProfile($profileId)
    {
        return $this->delete("profiles/{$profileId}");
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

    private function getProfileUsers($params)
    {
        return $this->get("profile-users/", $params);
    }

    public function getProfileUsersByProfileId($profileId, $page = 1, $limit = 100, $sortBy = 'id', $orderBy = 'desc')
    {
        return $this->getProfileUsers(
            [
                'profileId' => $profileId,
                'limit' => $limit,
                'page' => $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]
        );
    }

    public function getProfileUsersByUserId($userId, $page = 1, $limit = 100, $sortBy = 'id', $orderBy = 'desc')
    {
        return $this->getProfileUsers(
            [
                'userId' => $userId,
                'limit' => $limit,
                'page' => $page,
                'sortBy' => $sortBy,
                'orderBy' => $orderBy
            ]
        );
    }

    public function getResumes($params, $page = 1, $limit = 100, $sortBy = 'id', $orderBy = 'desc')
    {
        $params['limit'] = $limit;
        $params['page'] = $page;
        $params['sortBy'] = $sortBy;
        $params['orderBy'] = $orderBy;
        return $this->get("resumes/", $params);
    }

    public function deleteResumeById($resumeId)
    {
        return $this->delete("resumes/$resumeId");
    }
}
