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

    public function getTemplates()
    {
        return $this->get("/template");
    }
    
    public function getTemplate($templateId)
    {
        return $this->get("/template/{$templateId}");
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

    public function sendEmail($destEmail, $subject, $body, $isAuto, $sourceEmail = null)
    {
        return $this->post("/email/send", [ 'dest_emails' => $destEmail, 
                                            'subject' => $subject, 
                                            'body' => $body, 
                                            'is_auto' => $isAuto, 
                                            'source_email' => $sourceEmail, 
                                            ]);
    }

    public function sendSMS($destPhones, $body, $sendTime, $isAuto, $sourcePhone = null)
    {
        return $this->post("/sms/send", [ 'dest_phones' => $destPhones, 
                                          'body' => $body, 
                                          'send_time' => $sendTime, 
                                          'is_auto' => $isAuto, 
                                          'source_phone' => $sourcePhone, 
                                          ]);
    }
}
