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

    public function getTemplates($filterArray)
    {
        return $this->get("/template", $filterArray);
    }

    public function getTemplate($templateId)
    {
        return $this->get("/template/{$templateId}");
    }

    public function storeTemplate($changerId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("/template", $inputs);
    }

    public function updateTemplate($templateId, $changerId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->post("/template/{$templateId}", $inputs);
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

    public function sendEmail($destEmails, $subject, $body, $isAuto, $sourceEmail = null)
    {
        return $this->post("/email/send", [ 'dest_emails' => $destEmails, 
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

    public function notifyTemplateByUserId($templateType, $urseId, $variables)
    {
        return $this->post("/template/send/byUserId", [ 'template_type' => $templateType,
                                                        'user_id' => $urseId, 
                                                        'variables' => $variables, 
                                                        ]);
    }

    public function sendEmailByUserId($userIds, $subject, $body, $isAuto, $sourceEmail = null)
    {
        return $this->post("/email/send/byUserId", [ 'user_ids' => $userIds, 
                                                     'subject' => $subject, 
                                                     'body' => $body, 
                                                     'is_auto' => $isAuto, 
                                                     'source_email' => $sourceEmail, 
                                                     ]);
    }

    public function sendSMSByUserId($userIds, $body, $sendTime, $isAuto, $sourcePhone = null)
    {
        return $this->post("/sms/send/byUserId", [ 'user_ids' => $userIds, 
                                                   'body' => $body, 
                                                   'send_time' => $sendTime, 
                                                   'is_auto' => $isAuto, 
                                                   'source_phone' => $sourcePhone, 
                                                   ]);
    }

    public function getEmailLogs($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("/email/logs", $filterArray);
    }

    public function getEmailLog($emailLogId)
    {
        return $this->get("/email/log/{$emailLogId}");
    }

    public function getSmsLogs($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("/sms/logs", $filterArray);
    }
}
