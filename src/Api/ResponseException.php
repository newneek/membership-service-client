<?php
namespace Publy\ServiceClient\Api;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;

/**
 * Class ResponseException
 *
 */
class ResponseException extends \Exception
{
    /**
     * @var array
     */
    protected $errorDetails = [];

    public $responseBody;

    public function __construct(RequestException $e, $options = [])
    {
        $request = $e->getRequest();

        $message = '';
        if ($e instanceof ClientException) {
            $response = $e->getResponse();
            $message .= $response->getBody()->getContents();
        } elseif ($e instanceof ServerException) {
            $response = $e->getResponse();
            $message .= $response->getBody()->getContents();
        } elseif (! $e->hasResponse()) {
            $message .= $request->getBody()->getContents();
        }

        $this->errorDetails = [
            'url' => (string)$request->getUri(),
            'method' => $request->getMethod(),
            'body' => $request->getBody()->getContents()
        ];

        if (isset($options['postFields'])) {
            $this->errorDetails['postFields'] = $options['postFields'];
        }

        parent::__construct($message, $e->getCode());
    }

    public function getErrorDetails()
    {
        return $this->errorDetails;
    }
}
