<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;

class Amplitude2Service extends BaseApiService
{
    public function __construct($apiKey, $secretKey)
    {
        parent::__construct();

        $stack = \GuzzleHttp\HandlerStack::create(new \GuzzleHttp\Handler\CurlHandler());
        $stack->push(\GuzzleHttp\Middleware::retry($this->createRetryHandler()));
        $this->guzzle = new \GuzzleHttp\Client([
            'handler' => $stack,
            'auth' => [$apiKey, $secretKey]
        ]);

        $this->domain = 'https://amplitude.com/api/2';
        $this->apiUrl = "$this->domain/";
    }

    public function funnel($events, $segment, $start, $end, $conversionDays, $newOrActive)
    {
        $eventsString = '';
        foreach ($events as $index => $event) {
            if ($index != 0) {
                $eventsString .= '&';
            }
            $eventsString .= ('e={"event_type":' . '"' . $event['event_type'] . '"');
            if (isset($event['filters'])) {
                $eventsString .= ',"filters":';
                $eventsString .= json_encode($event['filters']);
            }
            $eventsString .= '}';
        }

        $result = $this->get(
            'funnels?' . $eventsString, [
            'cs' => $conversionDays * 60 * 60 * 24,
            'n' => $newOrActive,
            'start' => $start,
            'end' => $end,
            's' => json_encode($segment)
        ]);
        return $result;
    }

    public function eventSegmentation($event, $segment, $start, $end, $groupBy, $m)
    {
        $params = [
            'e' => json_encode($event),
            'm' => $m,
            'start' => $start,
            'end' => $end,
            's' => json_encode($segment)
        ];

        if ($groupBy) {
            $params['g'] = $groupBy;
        }
        $result = $this->get('events/segmentation', $params);
        return $result;
    }
}