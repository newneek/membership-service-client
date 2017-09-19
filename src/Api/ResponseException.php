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

    public function __construct(RequestException $e)
    {
        $message = '';
        if ($e instanceof ClientException) {
            $response = $e->getResponse();
            $message .= $response->getBody()->getContents();
        } elseif ($e instanceof ServerException) {
            $response = $e->getResponse();
            $message .= $response->getBody()->getContents();
        } elseif (! $e->hasResponse()) {
            $request = $e->getRequest();
            //Unsuccessful response, log what we can
            // $message .= ' [url] ' . $request->getUri();
            // $message .= ' [http method] ' . $request->getMethod();
            $message .= $request->getBody()->getContents();
        }

        // $message = $e->getMessage();
        // if ($e instanceof ClientException) {
        //     $response           = $e->getResponse();
        //     $responseBody       = $response->getBody()->getContents();
        //     $this->errorDetails = $responseBody;
        //     $message .= ' [details] ' . $this->errorDetails;
        // } elseif ($e instanceof ServerException) {
        //     $message .= ' [details] Server may be experiencing internal issues or undergoing scheduled maintenance.';
        //     $response = $e->getResponse();
        //     $message = $response->getBody()->getContents();
        // } elseif (! $e->hasResponse()) {
        //     $request = $e->getRequest();
        //     //Unsuccessful response, log what we can
        //     $message .= ' [url] ' . $request->getUri();
        //     $message .= ' [http method] ' . $request->getMethod();
        //     $message .= ' [body] ' . $request->getBody()->getContents();
        // }

        parent::__construct($message, $e->getCode());
    }

    public function getErrorDetails()
    {
        return $this->errorDetails;
    }
}
