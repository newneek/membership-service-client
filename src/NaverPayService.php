<?php

namespace Publy\ServiceClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Publy\ServiceClient\Api\BaseApiService;

class NaverPayService extends BaseApiService
{
    protected $partnerId;
    protected $clientId;
    protected $clientSecret;

    public function __construct($partnerId, $clientId, $clientSecret)
    {
        parent::__construct();
        
        $this->domain = 'https://apis.naver.com/' . $partnerId . '/naverpay/payments/recurrent/v1';
        $this->apiUrl = "$this->domain/";

        $this->partnerId = $partnerId;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
    }

    public function searchRecurrent($startTime, $page, $pageSize=100){
        $headers =
            [
                'Content-Type' => 'application/json',
                'X-Naver-Client-Id' => $this->clientId,
                'X-Naver-Client-Secret' => $this->clientSecret,
            ];

        $parsedTime = \Carbon\Carbon::parse($startTime);
        $startTime = $parsedTime->format('Ymd000000');
        $endTime = $parsedTime->format('Ymd235959');

        $fields = array(
            'startTime' => $startTime,
            'endTime' => $endTime,
            'state' => 'ALL',
            'pageNumber' => $page,
            'rowsPerPage' => $pageSize,
        );

        // TODO : sendPushWithRetry 테스트 완료 후, sendPushWithRetry 를 사용하도록 수정해야함
        $retryCount = 3;
        $client = new Client();
        while ($retryCount > 0) {
            try {
                $response = $client->request(
                    'POST',
                    $this->apiUrl . 'list',
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
}
