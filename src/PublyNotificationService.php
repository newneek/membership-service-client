<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyNotificationService extends BaseApiService
{

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function notifyByTemplate($templateType, $destEmail, $destName, $destPhone, $variables)
    {
        return $this->post("/template/send", [ 'template_type' => $templateType,
                                              'dest_email' => $destEmail, 
                                              'dest_name' => $destName, 
                                              'dest_phone' => $destPhone, 
                                              'variables' => $variables, 
                                              ]);
    }
}
