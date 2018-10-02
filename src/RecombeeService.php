<?php

namespace Publy\ServiceClient;

use Recombee\RecommApi\Client;
use Recombee\RecommApi\Requests as Reqs;
use Recombee\RecommApi\Exceptions as Ex;


class RecombeeService
{
    protected $client;

    public function __construct($databaseId, $secretToken)
    {
        $this->client = new Client($databaseId, $secretToken);
    }

    public function viewContent($userId, $contentId)
    {

    }

    public function completeContent($userId, $contentId)
    {// we use purchase for completion

    }
}