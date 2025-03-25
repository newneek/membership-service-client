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

        if(is_array($userId)){
            $userIds = array_map('strval', $userId);
        } else {
            $userIds = array_map('strval', array($userId));
        }


        $fields = array(
            'targetType' => 'userId',
            'targetIds'=> $userIds,
            'title' => $title,
            'body' => $msg,
            'data' => $data,
            'targetPlatforms'=>['android','ios']
        );

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
            'Authorization' => 'Bearer ' . $this->appKey
        ];

        $fields = [    
            'tags' => [
                [
                    "tags"=> $tags,
                    "subjectType" => "user",
                    "subjectId" => strval($userId)
                ]
            ]
        ];


        $retryCount = 3;
        $client = new Client();
        $url = $this->apiUrl . 'track';
        while ($retryCount > 0) {
            try {
                $response = $client->request(
                    'POST',  
                    $url, 
                    ['headers' => $headers, 'json' => $fields]
                );

                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

}
