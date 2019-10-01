<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use GuzzleHttp\Client;

class OneSignalService extends BaseApiService
{
    protected $appId;

    public function __construct($appId)
    {
        parent::__construct();

        $this->domain = 'https://onesignal.com/api/v1/notifications';
        $this->apiUrl = "$this->domain/";
        $this->appId = $appId;
    }

    public function sendPush($userId, $title, $msg, $sendTime){
        $headers =
            [
                'Content-Type' => 'application/json'
            ];

        $contents = array(
            "ko" => $msg,
            "en" => $msg
        );
        $headings = array(
            "ko" => $title,
            "en" => $title
        );

        $fields = array(
            'app_id' => $this->appId,
            'delayed_option' => 'timezone',
            'delivery_time_of_day' => $sendTime,
            'include_external_user_ids' => array($userId),
            'contents' => $contents,
            'headings' => $headings
        );
//        $fields = json_encode($fields);
        $retryCount = 3;
        $client = new Client();
        while ($retryCount > 0) {
            try {
                $response = $client->request('POST', $this->apiUrl, ['headers' => $headers,
                                                                     'json' => $fields]);
                return $response;
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }

    }

}