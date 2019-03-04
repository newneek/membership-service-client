<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class HunetService extends BaseApiService {

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function viewContent($hunetId, $contentId, $setId)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'content_id' => $contentId,
            'set_id' => $setId,
            'com_id' => 'publy'
        ];

        $properties = json_encode($properties);

        while ($retryCount > 0) {
            try {
                $this->get('hunet_test', ['event' => 'pageview_chapter', 'properties' => $properties]);
                return respond_success();
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function completeContent($hunetId, $contentId, $setId)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'content_id' => $contentId,
            'set_id' => $setId,
            'com_id' => 'publy'
        ];

        $properties = json_encode($properties);

        while ($retryCount > 0) {
            try {
                $this->get('hunet_test', ['event' => 'complete_chapter', 'properties' => $properties]);
                return respond_success();
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function rateSet($hunetId, $setId, $rating)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'set_id' => $setId,
            'rating' => $rating,
            'com_id' => 'publy'
        ];

        $properties = json_encode($properties);

        while ($retryCount > 0) {
            try {
                $this->get('hunet_test', ['event' => 'rate_set', 'properties' => $properties]);
                return respond_success();
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }

    public function addBookmark($hunetId, $setId)
    {
        $retryCount = 3;
        $properties = [
            'hunet_id' => $hunetId,
            'set_id' => $setId,
            'com_id' => 'publy'
        ];

        $properties = json_encode($properties);

        while ($retryCount > 0) {
            try {
                $this->get('hunet_test', ['event' => 'bookmark', 'properties' => $properties]);
                return respond_success();
            } catch (\Exception $e) {
                $retryCount--;
                if ($retryCount == 0) {
                    throw $e;
                }
            }
        }
    }
}