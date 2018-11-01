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
        try
        {
            $request =
                new RecombeeRequests\AddDetailView(
                    $userId,
                    static::SET_ITEM_PREFIX . $setId,
                    [ 'cascadeCreate' => true ]
                );
            $result = $this->client->send($request);
        } catch(\Exception $e) {
            report_async_error($e);
        }
    }

    public function addBookmark($userId, $setId)
    {
        try
        {
            $request =
                new RecombeeRequests\AddBookmark(
                    $userId,
                    static::SET_ITEM_PREFIX . $setId,
                    [ 'cascadeCreate' => true ]
                );
            $result = $this->client->send($request);

            return $result;
        } catch(\Exception $e) {
            report_async_error($e);
        }
    }

    public function deleteBookmark($userId, $setId)
    {
        try
        {
            $request =
                new RecombeeRequests\DeleteBookmark(
                    $userId,
                    static::SET_ITEM_PREFIX . $setId
                );
            $result = $this->client->send($request);
        } catch(\Exception $e) {
            report_async_error($e);
        }
    }

    public function AddRating($userId, $setId, $publyRating)
    {
        $recombeeRating = $publyRating - 2;
        try
        {
            $request =
                new RecombeeRequests\AddRating(
                    $userId,
                    static::SET_ITEM_PREFIX . $setId,
                    $recombeeRating,
                    [ 'cascadeCreate' => true ]
                );
            $result = $this->client->send($request);
            
            return $result;
        } catch(\Exception $e) {
            report_async_error($e);
        }
    }

    public function SetViewPortion($userId, $setId, $completedContents, $totalContents)
    {
        try
        {
            $request =
                new RecombeeRequests\SetViewPortion(
                    $userId,
                    static::SET_ITEM_PREFIX . $setId,
                    $completedContents / $totalContents,
                    [ 'cascadeCreate' => true ]
                );
            $result = $this->client->send($request);
        } catch(\Exception $e) {
            report_async_error($e);
        }
    }
}