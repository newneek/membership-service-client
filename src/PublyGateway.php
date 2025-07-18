<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyGateway extends BaseApiService
{

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/api";
    }

    public function saveContent($contentId)
    {
        return $this->post('/ai-search/save-content', [
            'contentId' => $contentId,
        ]);
    }

    public function findContent($contentId)
    {
        return $this->get("/ai-search/content/{$contentId}");
    }

}
