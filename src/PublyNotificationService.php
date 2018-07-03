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
        return $this->post("/template/send", [
            'template_type' => $templateType,
            'dest_email' => $destEmail,
            'dest_name' => $destName,
            'dest_phone' => $destPhone,
            'variables' => $variables,
        ]);
    }

    public function sendEmail($destEmails, $subject, $body, $isAuto, $sourceEmail = null, $sourceName = null)
    {
        return $this->post("/email/send", [
            'dest_emails' => $destEmails,
            'subject' => $subject,
            'body' => $body,
            'is_auto' => $isAuto,
            'source_email' => $sourceEmail,
            'source_name' => $sourceName
        ]);
    }

    public function sendSMS($destPhones, $body, $sendTime, $isAuto, $sourcePhone = null)
    {
        return $this->post("/sms/send", [
            'dest_phones' => $destPhones,
            'body' => $body,
            'send_time' => $sendTime,
            'is_auto' => $isAuto,
            'source_phone' => $sourcePhone,
        ]);
    }

    // TODO : deprecated
    public function sendNotificationTemplateByUser($templateType, $userId, $variables)
    {
        return $this->post("/template/send/byUserId", [
            'template_type' => $templateType,
            'user_id' => $userId,
            'variables' => $variables,
        ]);
    }

    public function sendEmailByUsers($userIds, $subject, $body, $isAuto, $sourceEmail = null, $sourceName = null)
    {
        return $this->post("/email/send/byUserId", [
            'user_ids' => $userIds,
            'subject' => $subject,
            'body' => $body,
            'is_auto' => $isAuto,
            'source_email' => $sourceEmail,
            'source_name' => $sourceName
        ]);
    }

    public function sendSMSByUsers($userIds, $body, $sendTime, $isAuto, $sourcePhone = null)
    {
        return $this->post("/sms/send/byUserId", [
            'user_ids' => $userIds,
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

    public function getSmsLog($smsLogId)
    {
        return $this->get("/sms/log/{$smsLogId}");
    }

    public function eventUserSignup($userId)
    {
        return $this->post("/event/user_signup", ['user_id' => $userId]);
    }

    public function eventUserLogin($userId)
    {
        return $this->post("/event/user_login", ['user_id' => $userId]);
    }

    public function eventSetReviewCreated($setReview)
    {
        return $this->post("/event/set_review_created", ['set_review' => $setReview]);
    }

    public function eventSetReviewUpdated($setReview)
    {
        return $this->post("/event/set_review_updated", ['set_review' => $setReview]);
    }

    public function eventOrderStatusChanged($order, $oldStatus, $newStatus)
    {
        return $this->post("/event/order_status_changed", [
            'order' => $order,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }

    public function eventSetIsActiveChanged($set, $oldIsActive, $newIsActive)
    {
        $inputs = [
            'set' => $set,
            'old_is_active' => $oldIsActive,
            'new_is_active' => $newIsActive
        ];
        return $this->post("/event/set_is_active_changed", $inputs);
    }

    public function eventSubscriptionStatusChanged(
        $subscription,
        $nextPayment,
        $creditCard,
        $oldStatus,
        $newStatus,
        $withPayment
    ) {
        return $this->post("/event/subscription_status_changed",
            [
                'subscription' => $subscription,
                'next_payment' => $nextPayment,
                'credit_card' => $creditCard,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'with_payment' => $withPayment
            ]);
    }

    public function eventSubscriptionStatusChanged2(
        $subscription,
        $nextPayment,
        $creditCard,
        $oldStatus,
        $newStatus,
        $withPayment,
        $usedPoints
    ) {
        return $this->post("/event/subscription_status_changed",
            [
                'subscription' => $subscription,
                'next_payment' => $nextPayment,
                'credit_card' => $creditCard,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'with_payment' => $withPayment,
                'used_points' => $usedPoints
            ]);
    }

    public function eventSubscriptionRenewalHistoryCreated(
        $subscriptionRenewalHistory,
        $renewalCount
    ) {
        return $this->post("/event/subscription_renewal_history_created",
            [
                'subscription_renewal_history' => $subscriptionRenewalHistory,
                'renewal_count' => $renewalCount
            ]);
    }

    public function notifyComingSubscriptionExpiration()
    {
        return $this->post("/event/coming_subscription_expiration", []);
    }

    public function reserveAlarm($changerId, $type, $userId, $alarmDate)
    {
        $inputs = [
            'changer_id' => $changerId,
            'type' => $type,
            'user_id' => $userId,
            'alarm_date' => $alarmDate
        ];
        return $this->put("/reserved_alarm", $inputs);
    }

    public function alarmReservedAlarms()
    {
        return $this->post("/reserved_alarm/alarm");
    }

    public function triggerNotificationTemplate()
    {
        return $this->post("/trigger/all");
    }

    public function eventReferralSuccess($userId, $refereeId, $delta)
    {
        return $this->post("/event/referral_success",
            [
                'referrer_id' => $userId,
                'referee_id' => $refereeId,
                'point' => $delta
            ]
        );
    }
}
