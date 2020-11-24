<?php

namespace Publy\ServiceClient;

use GuzzleHttp\Exception\GuzzleException;
use Publy\ServiceClient\Api\BaseApiService;
use GuzzleHttp\Client;

class OneSignalService extends BaseApiService
{
    protected $appId;
    protected $appKey;

    public function __construct($appId, $appKey)
    {
        parent::__construct();

        $this->domain = 'https://onesignal.com/api/v1';
        $this->apiUrl = "$this->domain/";
        $this->appId = $appId;
        $this->appKey = $appKey;
    }

    public function sendPush($userId, $title, $msg, $sendTime = null, $data = []){
        $headers =
            [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $this->appKey
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
            'include_external_user_ids' => array($userId),
            'contents' => $contents,
            'headings' => $headings,
            'data' => $data
        );
        if (isset($sendTime)) {
            $fields['delayed_option'] = 'timezone';
            $fields['delivery_time_of_day'] = $sendTime;
        }

        $retryCount = 3;
        $client = new Client();
        while ($retryCount > 0) {
            try {
                $response = $client->request('POST', $this->apiUrl . 'notifications', ['headers' => $headers,
                                                                     'json' => $fields]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }

    }

    public function getUsers($offset){
        $headers =
            [
                'Authorization' => 'Basic ' . $this->appKey
            ];

        $retryCount = 3;
        $client = new Client();
        $url = $this->apiUrl . 'players?app_id=' . $this->appId . '&offset=' . $offset;
        while ($retryCount > 0) {
            try {
                $response = $client->request('GET',  $url, ['headers' => $headers]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (GuzzleException $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function updateTags($playerId, $tags)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $this->appKey
        ];

        $fields = [
            'app_id' => $this->appId,
            'tags' => $tags
        ];

        $retryCount = 3;
        $client = new Client();
        $url = $this->apiUrl . 'players/' . $playerId;
        while ($retryCount > 0) {
            try {
                $response = $client->request('PUT',  $url, ['headers' => $headers, 'json' => $fields]);
                return json_decode($response->getBody()->getContents(), true);
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function updateTagsByExternalUserId($externalUserId, $tags)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $this->appKey
        ];

        $fields = [
            'tags' => $tags
        ];

        $retryCount = 3;
        $client = new Client();
        $url = $this->apiUrl . 'apps/' . $this->appId . '/users/' . $externalUserId;
        while ($retryCount > 0) {
            try {
                $response = $client->request('PUT',  $url, ['headers' => $headers, 'json' => $fields]);
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
