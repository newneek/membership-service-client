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

    public function getEmbeddedHtml($link, $contentMaxWidht) {

        // https://developers.facebook.com/docs/plugins/oembed-endpoints
        $result = $this->get('/plugins/post/oembed.json',
            [
                'url' => $link,
                'omitscript' => true,
                'maxwidth' => $contentMaxWidht
            ]
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

    public function getEmbeddedHtml($link, $contentMaxWidht) {

        // https://dev.twitter.com/web/embedded-tweets
        // https://developer.twitter.com/en/docs/tweets/post-and-engage/api-reference/get-statuses-oembed
        $result = $this->get('/oembed',
            [
                'url' => $link,
                'maxwidth' => $contentMaxWidht
            ]
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

    public function getEmbeddedHtml($link, $contentMaxWidht) {

        // https://www.instagram.com/developer/embedding/
        $result = $this->get('/oembed',
            [
                'url' => $link,
                'maxwidth' => $contentMaxWidht
            ]
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

    public function getEmbeddedHtml($type, $link, $contentMaxWidht = 320) {
        switch ($type) {
            case PublyExtraService::SOCIAL_PROOF_TYPE_FACEBOOK:
                return $this->facebookEmbeddedService->getEmbeddedHtml($link, $contentMaxWidht);
            case PublyExtraService::SOCIAL_PROOF_TYPE_TWITTER:
                return $this->twitterEmbeddedService->getEmbeddedHtml($link, $contentMaxWidht);
            case PublyExtraService::SOCIAL_PROOF_TYPE_INSTAGRAM:
                return $this->instagramEmbeddedService->getEmbeddedHtml($link, $contentMaxWidht);
            default:
                throw new \Exception('Unknown type');
        }
    }
}