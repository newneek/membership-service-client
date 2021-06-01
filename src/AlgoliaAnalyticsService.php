<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiServiceWithHeader;
use Publy\ServiceClient\Api\ResponseException;

class AlgoliaAnalyticsService extends BaseApiServiceWithHeader
{
    public const DEFAULT_INDEX_KEY = 'all';

    private $ALGOLIA_APP_ID;
    private $ALGOLIA_API_KEY;

    public function __construct($ALGOLIA_APP_ID, $ALGOLIA_API_KEY)
    {
        parent::__construct();

        $this->domain = "https://analytics.algolia.com";
        $this->apiUrl = "$this->domain/";
        $this->ALGOLIA_APP_ID = $ALGOLIA_APP_ID;
        $this->ALGOLIA_API_KEY = $ALGOLIA_API_KEY;
    }

    private function getHeaders(): array
    {
        return [
            "X-Algolia-Application-Id" => $this->ALGOLIA_APP_ID,
            "X-Algolia-API-Key" => $this->ALGOLIA_API_KEY,
        ];
    }

    /**
     * @throws ResponseException
     */
    public function getTopSearchResult($queryParams = [])
    {
        return $this->get("2/searches", $queryParams, $this->getHeaders());
    }
}
