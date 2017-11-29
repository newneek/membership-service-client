<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\PublyExtraService;

class FacebookEmbeddedService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();

        $this->domain = 'https://www.facebook.com';
        $this->apiUrl = "$this->domain";
    }

    public function getEmbeddedHtml($link) {

        $result = $this->get('/plugins/post/oembed.json',
            [ 'url' => $link ]
        );

        return $result['html'];
    }
}

class TwitterEmbeddedService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();

        $this->domain = 'https://publish.twitter.com';
        $this->apiUrl = "$this->domain";

    }

    public function getEmbeddedHtml($link) {

        $result = $this->get('/oembed',
            [ 'url' => $link ]
        );

        return $result['html'];
    }

}

class InstagramEmbeddedService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct();

        $this->domain = 'https://api.instagram.com';
        $this->apiUrl = "$this->domain";
    }

    public function getEmbeddedHtml($link) {

        $result = $this->get('/oembed',
            [ 'url' => $link ]
        );

        return $result['html'];
    }
}

class SocialEmbeddedService{

    private $facebookEmbeddedService;
    private $twitterEmbeddedService;
    private $instagramEmbeddedService;

    public function __construct() {
        $this->facebookEmbeddedService = new FacebookEmbeddedService();
        $this->twitterEmbeddedService = new TwitterEmbeddedService();
        $this->instagramEmbeddedService = new InstagramEmbeddedService();

    }

    public function getEmbeddedHtml($type, $link) {
        switch ($type) {
            case PublyExtraService::SOCIAL_PROOF_TYPE_FACEBOOK:
                return $this->facebookEmbeddedService->getEmbeddedHtml($link);
            case PublyExtraService::SOCIAL_PROOF_TYPE_TWITTER:
                return $this->twitterEmbeddedService->getEmbeddedHtml($link);
            case PublyExtraService::SOCIAL_PROOF_TYPE_INSTAGRAM:
                return $this->instagramEmbeddedService->getEmbeddedHtml($link);
            default:
                throw new \Exception('Unknown type');
        }
    }
}