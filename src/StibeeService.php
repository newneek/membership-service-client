<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class StibeeService extends BaseApiService
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        parent::__construct();

        $this->domain = 'https://api.stibee.com';
        $this->apiUrl = "$this->domain/v1/";
        $this->apiKey = $apiKey;
    }

    public function subscribeToList($listId, $email, $name, $marketingEmailAgreed)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->postWithHeader("lists/{$listId}/subscribers", [
                    'eventOccurredBy' => 'SUBSCRIBER',
                    'subscribers' => [
                        [
                            'email' => $email,
                            'name'=> $name,
                            '$ad_agreed' => $marketingEmailAgreed ? 'Y' : 'N'
                        ]
                    ]
                ],[
                    'AccessToken' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]);
                return $result;

            } catch (ResponseException $e) {
                // for any response exception, retry
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function deleteSubscriber($listId, $email)
    {
        $retryCount = 3;
        while ($retryCount > 0) {
            try {
                $result = $this->deleteWithHeader("lists/{$listId}/subscribers", [
                    $email
                ],[
                    'AccessToken' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]);
                return $result;

            } catch (ResponseException $e) {
                // for any response exception, retry
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function subscribesToList($listId, $users, $marketingEmailAgreed)
    {
        $retryCount = 3;
        foreach ($users as $user) {
            $subscribers[] = [
                'email' => $user['email'],
                'name' => $user['name'],
                '$ad_agreed' => $marketingEmailAgreed ? 'Y' : 'N'
            ];
        }   
        
        while ($retryCount > 0) {
            try {
                $result = $this->postWithHeader("lists/{$listId}/subscribers", [
                    'eventOccurredBy' => 'SUBSCRIBER',
                    'subscribers' => $subscribers,
                ],[
                    'AccessToken' => $this->apiKey,
                    'Content-Type' => 'application/json'
                ]);
                return $result;

            } catch (ResponseException $e) {
                // for any response exception, retry
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function sendEmail($endpintURL, $email){
        $headers =
        [
            'Content-Type' => 'application/json',
            'AccessToken' => $this->appKey
        ];

        $client = new Client();
        while ($retryCount > 0) {
            try {
                $response = $client->request(
                    'POST',
                    $endpintURL,
                    [
                        'headers' => $headers,
                        'body' => [
                            'email' => $email
                        ]
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