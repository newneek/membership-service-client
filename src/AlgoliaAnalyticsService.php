<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class AlgoliaAnalyticsService extends BaseApiService
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

    /**
     * @throws ResponseException
     */
    public function getTopSearchResult($queryParams = [])
    {
        return $this->getWithHeader(
            "2/searches",
            $queryParams,
            $this->getHeaders()
        );
    }

    /**
     * @return array
     */
    private function getHeaders(): array
    {
        $headers = [
            "X-Algolia-Application-Id" => $this->ALGOLIA_APP_ID,
            "X-Algolia-API-Key" => $this->ALGOLIA_API_KEY,
        ];
        return $headers;
    }
}
