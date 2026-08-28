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

    /**
     * 세그먼트 대상 푸시 발송.
     * targetPlatforms는 의도적으로 싣지 않는다 — 세그먼트가 앱/웹 대상을 이미 결정하며,
     * 플랫폼을 지정하면 웹 세그먼트 발송 시 교집합이 비어 조용히 0명 발송된다.
     * 재시도 루프를 의도적으로 두지 않는다 — 호출부 크론이 매분 재시도하며,
     * Idempotency-Key가 이중 발송을 막는다. 실패는 그대로 throw.
     *
     * @param array $segmentIds FlareLane 세그먼트 ID 배열 (최대 5개)
     * @param string $title
     * @param string $body
     * @param array $data 랜딩 데이터 (예: ['content' => '8157'])
     * @param string|null $imageUrl
     * @param string|null $idempotencyKey 재시도 시 이중 발송 방지 키
     * @param string|null $url 클릭 랜딩 URL — 웹 푸시는 url이 없으면 클릭해도 이동하지 않는다 (data는 앱 전용)
     * @return array 디코딩된 응답 (성공 시 data.id에 notification id)
     * @throws \Exception
     */
    public function sendPushToSegments($segmentIds, $title, $body, $data = [], $imageUrl = null, $idempotencyKey = null, $url = null)
    {
        if (empty($segmentIds)) {
            throw new \InvalidArgumentException('segmentIds is empty');
        }
        if (count($segmentIds) > 5) {
            throw new \InvalidArgumentException('segmentIds max count is 5');
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->appKey,
        ];
        if ($idempotencyKey !== null) {
            $headers['Idempotency-Key'] = $idempotencyKey;
        }

        $fields = [
            'targetType' => 'segment',
            'targetIds' => array_map('strval', array_values($segmentIds)),
            'title' => $title,
            'body' => $body,
        ];
        if (!empty($data)) {
            $fields['data'] = $data;
        }
        if (!empty($imageUrl)) {
            $fields['imageUrl'] = $imageUrl;
        }
        if (!empty($url)) {
            $fields['url'] = $url;
        }

        $client = new Client();
        $response = $client->request(
            'POST',
            $this->apiUrl . 'notifications',
            [
                'headers' => $headers,
                'json' => $fields,
                'timeout' => 10,
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
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

    public function trackEvent($userId, $eventName, $eventData = [])
    {
        $headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->appKey
        ];

        $fields = [
            'events' => [
                [
                    'name' => $eventName,
                    'data' => $eventData,
                    'subjectType' => 'user',
                    'subjectId' => strval($userId)
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
