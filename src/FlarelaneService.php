<?php

namespace Publy\ServiceClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Publy\ServiceClient\Api\BaseApiService;

class FlarelaneService extends BaseApiService
{
    protected $appKey;

    public function __construct($appId, $appKey)
    {
        parent::__construct();
        
        $this->domain = 'https://api.flarelane.com/v1/projects/' . $appId;
        $this->apiUrl = "$this->domain/";
        $this->appKey = $appKey;
    }

    public function sendPush($userId, $title, $msg, $data = []){
        $headers =
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->appKey
            ];

        $userId = [strval($userId)];

        $fields = array(
            'targetType' => 'userId',
            'targetIds'=> $userId,
            'title' => $title,
            'body' => $msg,
            'data' => $data
        );

        \Log::debug('FlarelaneService::sendPush', $fields);
        // TODO : sendPushWithRetry 테스트 완료 후, sendPushWithRetry 를 사용하도록 수정해야함
        $retryCount = 3;
        $client = new Client();
        while ($retryCount > 0) {
            try {
                $response = $client->request(
                    'POST',
                    $this->apiUrl . 'notifications',
                    [
                        'headers' => $headers,
                        'json' => $fields
                    ]
                );
                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    private function sendPushWithRetry($headers, $fields)
    {
        $retryCount = 3;
        $client = new Client();
        while ($retryCount > 0) {
            try {
                $response = $client->request(
                    'POST',
                    $this->apiUrl . 'notifications',
                    [
                        'headers' => $headers,
                        'json' => $fields
                    ]
                );
                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function updateTags($userId, $tags)
    {
        $headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Basic ' . $this->appKey
        ];

        $fields = [
            'tags' => [
                "tags"=> $tags,
                "subjectType" => "userId",
                "subjectId" => $userId
            ]
        ];

        $retryCount = 3;
        $client = new Client();
        $url = $this->apiUrl . '/track';
        while ($retryCount > 0) {
            try {
                $response = $client->request('POST',  $url, ['headers' => $headers, 'body' => $fields]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

}
