<?php

namespace Publy\ServiceClient;

use Recombee\RecommApi\Client as RecombeeClient;
use Recombee\RecommApi\Requests as RecombeeRequests;

class RecombeeService
{
    protected $client;
    const SET_ITEM_PREFIX = 'set:';

    public function __construct($databaseId, $secretToken)
    {
        $this->client = new RecombeeClient($databaseId, $secretToken);
    }

    public function viewContent($userId, $setId, $contentId)
    {
        // contentId in not used currently
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $request =
                    new RecombeeRequests\AddDetailView(
                        $userId,
                        static::SET_ITEM_PREFIX . $setId,
                        ['cascadeCreate' => true]
                    );
                $result = $this->client->send($request);

                return $result;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    report_async_error($e);
                    return null;
                }
            }
        }
    }

    public function addBookmark($userId, $setId)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $request =
                    new RecombeeRequests\AddBookmark(
                        $userId,
                        static::SET_ITEM_PREFIX . $setId,
                        ['cascadeCreate' => true]
                    );
                $result = $this->client->send($request);

                return $result;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    report_async_error($e);
                    return null;
                }
            }
        }
    }

    public function deleteBookmark($userId, $setId)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $request =
                    new RecombeeRequests\DeleteBookmark(
                        $userId,
                        static::SET_ITEM_PREFIX . $setId
                    );
                $result = $this->client->send($request);

                return $result;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    report_async_error($e);
                    return null;
                }
            }
        }
    }

    public function addRating($userId, $setId, $publyRating)
    {

        $recombeeRating = $publyRating - 2;
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $request =
                    new RecombeeRequests\AddRating(
                        $userId,
                        static::SET_ITEM_PREFIX . $setId,
                        $recombeeRating,
                        ['cascadeCreate' => true]
                    );
                $result = $this->client->send($request);

                return $result;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    report_async_error($e);
                    return null;
                }
            }
        }
    }

    public function setViewPortion($userId, $setId, $completedContents, $totalContents)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $request =
                    new RecombeeRequests\SetViewPortion(
                        $userId,
                        static::SET_ITEM_PREFIX . $setId,
                        $completedContents / $totalContents,
                        ['cascadeCreate' => true]
                    );
                $result = $this->client->send($request);

                return $result;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    report_async_error($e);
                    return null;
                }
            }
        }
    }

    public function getRecommendItemsToUser($userId, $count)
    {
        try {
            $request =
                new RecombeeRequests\RecommendItemsToUser(
                    $userId,
                    $count,
                    [
                        'returnProperties' => true
                    ]
                );
            $result = $this->client->send($request);

            return $result;
        } catch (\Exception $e) {
            report_async_error($e);
            return null;
        }
    }
}