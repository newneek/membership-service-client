<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;
use Aws\Sqs\SqsClient;

class SqsService extends BaseApiService
{
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