<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;
use Aws\Sqs\SqsClient;

class SqsService extends BaseApiService
{
    const FEED_WORKER_MESSAGE_TYPE_CREATE_USER_FEED = 'create-user-feed';
    const FEED_WORKER_MESSAGE_TYPE_UPDATE_USER_FEED = 'update-user-feed';
    const FEED_WORKER_MESSAGE_TYPE_REFRESH_USER_FEED = 'refresh-user-feed';
    const FEED_WORKER_MESSAGE_TYPE_UPDATE_OR_CREATE_SET_META_CACHE = 'update-or-create-set-meta-cache';
    const FEED_WORKER_MESSAGE_TYPE_DELETE_SET_META_CACHE = 'delete-set-meta-cache';
    const FEED_WORKER_MESSAGE_TYPE_REFRESH_ALL_SET_META_CACHE = 'refresh-all-set-meta-cache';

    protected $sqsClient;

    public function __construct()
    {
        parent::__construct();

        $this->sqsClient = new SqsClient([
            'region' => 'ap-northeast-2',
            'version' => 'latest'
        ]);
    }

    public function sendMessage($queueUrl, $messageBody)
    {
        $params = [
            'QueueUrl' => $queueUrl,
            'MessageBody' => $messageBody
        ];

        try {
            return $this->sqsClient->sendMessage($params);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function sendDelayMessage($delaySeconds, $queueUrl, $messageBody)
    {
        $params = [
            'DelaySeconds' => $delaySeconds,
            'QueueUrl' => $queueUrl,
            'MessageBody' => $messageBody
        ];

        try {
            return $this->sqsClient->sendMessage($params);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}