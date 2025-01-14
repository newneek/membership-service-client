<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyNotificationService extends BaseApiService
{
    const PUSH_NOTIFICATION_TYPE_NEW_CONTENT = 1;
    const PUSH_NOTIFICATION_TYPE_NOTICE = 2;
    const PUSH_NOTIFICATION_TYPE_PROMOTION_EVENT = 3;
    const PUSH_NOTIFICATION_TYPE_PUBLISHED_CONTENT = 4;
    const PUSH_NOTIFICATION_TYPE_COMMUNITY = 5;
    const PUSH_NOTIFICATION_TYPE_QNA = 6;

    const STRING_PUSH_NOTIFICATION_TYPE = [
        PublyNotificationService::PUSH_NOTIFICATION_TYPE_NEW_CONTENT => '신규 콘텐츠',
        PublyNotificationService::PUSH_NOTIFICATION_TYPE_NOTICE => '공지',
        PublyNotificationService::PUSH_NOTIFICATION_TYPE_PROMOTION_EVENT => '이벤트/프로모션',
        PublyNotificationService::PUSH_NOTIFICATION_TYPE_PUBLISHED_CONTENT => '발행 알림',
        PublyNotificationService::PUSH_NOTIFICATION_TYPE_COMMUNITY => '커뮤니티',
        PublyNotificationService::PUSH_NOTIFICATION_TYPE_QNA => 'Q&A'
    ];

    const NOTIFICATION_STATUS_TYPE_BASIC = 'push_notification_agree';
    const NOTIFICATION_STATUS_TYPE_ROUTINE = 'push_notification_for_routine_agree';
    const NOTIFICATION_STATUS_TYPE_CHALLENGE = 'push_notification_for_challenge_agree';

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

    public function notifyBatchByTemplate(string $templateType, array $batchData)
    {
        return $this->post("/template/send_batch", [
            'template_type' => $templateType,
            'batch_data' => $batchData,
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

    public function eventUserSignup($userId, $product, $method, $sendMail = 1)
    {
        return $this->post("/event/user_signup", [
            'user_id' => $userId,
            'product' => $product,
            'method' => $method,
            'send_mail' => $sendMail,
        ]);
    }

    public function eventUserLogin($userId, $product, $method)
    {
        return $this->post("/event/user_login", [
            'user_id' => $userId,
            'product' => $product,
            'method' => $method,
        ]);
    }

    public function eventUserTurnoffPushNotification($userId, $product)
    {
        return $this->post("/event/user_turnoff_push_notification_agree", [
            'user_id' => $userId,
            'product' => $product
        ]);
    }

    public function eventSetReviewCreated($setReview)
    {
        return $this->post("/event/set_review_created", ['set_review' => $setReview]);
    }

    public function eventSetReviewUpdated($setReview)
    {
        return $this->post("/event/set_review_updated", ['set_review' => $setReview]);
    }

    public function eventOrderStatusChanged($order, $oldStatus, $newStatus, $usedPoints = 0)
    {
        return $this->post("/event/order_status_changed", [
            'order' => $order,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'used_points' => $usedPoints
        ]);
    }

    public function eventSetStatusChanged($set, $oldStatus, $newStatus)
    {
        $inputs = [
            'set' => $set,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ];
        return $this->post("/event/set_status_changed", $inputs);
    }

    public function eventSubscriptionStatusChanged(
        $subscription,
        $nextPayment,
        $creditCard,
        $oldStatus,
        $newStatus,
        $withPayment
    ) {
        return $this->post(
            "/event/subscription_status_changed",
            [
                'subscription' => $subscription,
                'next_payment' => $nextPayment,
                'credit_card' => $creditCard,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'with_payment' => $withPayment
            ]
        );
    }

    public function eventSubscriptionStatusChanged2(
        $subscription,
        $nextPayment,
        $creditCard,
        $oldStatus,
        $newStatus,
        $usedPoints
    ) {
        return $this->post(
            "/event/subscription_status_changed",
            [
                'subscription' => $subscription,
                'next_payment' => $nextPayment,
                'credit_card' => $creditCard,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'used_points' => $usedPoints
            ]
        );
    }

    public function eventRequestDeleteAccount($userId, $product)
    {
        return $this->post("/event/request_delete_account", [
            'user_id' => $userId,
            'product' => $product,
        ]);
    }

    public function eventDeleteAccount($changerId, $userId, $product)
    {
        return $this->post("/event/delete_account", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'product' => $product,
        ]);
    }

    public function eventSubscriptionNextPlanChanged($subscription)
    {
        return $this->post("/event/subscription_next_plan_changed", ['subscription' => $subscription]);
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

    public function sendVoucher($voucher)
    {
        return $this->post("/event/send_voucher",
            [
                'voucher' => $voucher
            ]);
    }

    public function eventSetDraftStatusChanged($setDraft, $oldStatus, $newStatus)
    {
        return $this->post("/event/set_draft_status_changed", [
            'set_draft' => $setDraft,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
    }

    public function eventSetDraftLikeCreated($setDraftLike)
    {
        return $this->post("/event/set_draft_like_created", [
            'set_draft_like' => $setDraftLike
        ]);
    }

    public function sendPush($userId, $headings, $contents, $sendTime = null, $data = []) {
        return $this->post("/push/send", [
            'user_id' => $userId,
            'headings' => $headings,
            'contents' => $contents,
            'send_time' => $sendTime,
            'data' => $data
        ]);
    }

    public function sendPushAfter($userIds, $headings, $contents, $sendTime, $data = []) {
        return $this->post("/push/send-after", [
            'user_ids' => $userIds,
            'headings' => $headings,
            'contents' => $contents,
            'send_time' => $sendTime,
            'data' => $data
        ]);
    }

    public function sendManyPush($pushMessages) {
        return $this->post("/push/send-many", [
            'pushMessages' => $pushMessages
        ]);
    }

    public function eventAmplitude($userId, $eventType, $eventProperties, $userProperties = []){
        return $this->post("/amplitude/event", [
            'user_id' => $userId,
            'event_type' => $eventType,
            'event_properties' => $eventProperties,
            'user_properties' => $userProperties
        ]);
    }

    public function eventAmplitudeBatch($amplitudeEvents){
        return $this->post("/amplitude/event_batch", [
            'amplitude_events' => $amplitudeEvents,
        ]);
    }

    public function identifyAmplitude($userId, $userProperties){
        return $this->post('/amplitude/identify', [
            'user_id' => $userId,
            'user_properties' => $userProperties
        ]);
    }

    public function eventUserCompleteProfile($userId)
    {
        return $this->post('/event/user_complete_profile', [
            'user_id' => $userId
        ]);
    }

    public function eventUserUpdateProfile($userId, $userSegment)
    {
        return $this->post('/event/user_update_profile', [
            'user_id' => $userId,
            'user_segment' => $userSegment
        ]);
    }

    public function eventVoucherUseHistoryCreated($voucherUseHistory)
    {
        return $this->post("/event/voucher_use_history_created", [
            'voucher_use_history' => $voucherUseHistory
        ]);
    }

    public function eventVoucherUseHistoryStatusChanged($voucherUseHistory)
    {
        return $this->post("/event/voucher_use_history_status_changed", [
            'voucher_use_history' => $voucherUseHistory
        ]);
    }

    public function eventUserRoutineCreated($userId)
    {
        $inputs = [
            'user_id' => $userId,
        ];
        return $this->post("/event/user_routine_created", $inputs);
    }

    public function eventUserRoutineUpdated($userId)
    {
        $inputs = [
            'user_id' => $userId,
        ];
        return $this->post("/event/user_routine_updated", $inputs);
    }

    public function eventCouponV2WasCreated($couponV2)
    {
        $inputs = [
            'coupon_v2' => $couponV2
        ];
        return $this->post("/event/coupon_v2_was_created", $inputs);
    }

    public function eventFreeTrialAppInstalled($userId, $rewardedPoints)
    {
        $inputs = [
            'user_id' => $userId,
            'rewarded_points' => $rewardedPoints
        ];

        return $this->post('/event/free_trial_app_installed', $inputs);
    }

    public function eventFreeTrialExpiredByUser($userId, $usablePoint)
    {
        $inputs = [
            'user_id' => $userId,
            'usable_point' => $usablePoint
        ];

        return $this->post('/event/free_trial_expired_by_user', $inputs);
    }

    public function eventCommentReplied($replyComment)
    {
        $inputs = [
            'reply_comment' => $replyComment
        ];

        return $this->post('/event/comment_replied', $inputs);
    }

    public function eventCommentCreated($comment)
    {
        $inputs = [
            'comment' => $comment
        ];

        return $this->post('/event/comment_created', $inputs);
    }

    public function eventQuestionCreated($question)
    {
        $inputs = [
            'question' => $question
        ];

        return $this->post('/event/question_created', $inputs);
    }

    public function eventPromotionReferralPointRewarded($pointHistory)
    {
        $inputs = ['pointHistory' => $pointHistory];

        return $this->post('/event/promotion_referral_point_rewarded', $inputs);
    }

    public function eventDailyFirstChallengeHistoryCreated($userId, $challenges)
    {
        $inputs = [
            'challenges' => $challenges,
            'user_id' => $userId
        ];

        return $this->post('/event/daily_first_challenge_history_created', $inputs);
    }

    // deprecated
    public function eventDailyFirstSetReviewChallengeHistoryCreated($userId, $challenges)
    {
        $inputs = [
            'challenges' => $challenges,
            'user_id' => $userId
        ];

        return $this->post('/event/daily_first_set_review_challenge_history_created', $inputs);
    }

    public function eventUserCompleteOnboardingChallenge($userId)
    {
        $inputs = [
            'user_id' => $userId
        ];

        return $this->post('event/user_complete_onboarding_challenge', $inputs);
    }

    public function getNotificationMessages($page = 1, $limit = 5, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("notification_message", $filterArray);
    }

    public function getNotificationMessage($notificationMessageId)
    {
        return $this->get("notification_message/{$notificationMessageId}");
    }

    public function createNotificationMessage($inputs)
    {
        return $this->post("notification_message/send", $inputs);
    }

    public function updateNotificationMessage($changerId, $notificationMessageId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("notification_message/{$notificationMessageId}", $inputs);
    }

    public function deleteNotificationMessage($changerId, $notificationMessageId)
    {
        $inputs['changer_id'] = $changerId;
        return $this->post("notification_message/{$notificationMessageId}/delete", $inputs);
    }

    public function getNotificationTemplates($filterArray)
    {
        return $this->get("notification_template", $filterArray);
    }

    public function createNotificationTemplate($changerId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->post("notification_template", $inputs);
    }

    public function getNotificationTemplate($templateId)
    {
        return $this->get("notification_template/{$templateId}");
    }

    public function updateNotificationTemplate($templateId, $changerId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("notification_template/{$templateId}", $inputs);
    }

    public function sendNotificationByTemplate($templateType, $variables, $userIds, $analyticsEvent = [])
    {
        $inputs = [
            'variables' => $variables,
            'user_ids' => $userIds,
            'analytics_event' => $analyticsEvent,
        ];

        return $this->post("notification_template/template_type/{$templateType}/send", $inputs);
    }

    public function getNotificationSendHistoriesByUserId($userId, $page = 1, $limit = 5, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("notification_send_history/user/{$userId}", $filterArray);
    }

    public function getNotificationSendHistoryCountByUserId($userId, $filterArray = [])
    {
        return $this->get("notification_send_history/user/{$userId}/count", $filterArray);
    }

    public function getNotificationSendTargetUserIdsByNotificationId($notificationMessageId)
    {
        return $this->get("/notification_send_history/notificationMessage/{$notificationMessageId}/user_ids");
    }

    public function sendSolapiMessage($templateType, $variables, $destinationPhone)
    {
        $inputs = [
            'template_type' => $templateType,
            'variables' => $variables,
            'dest_phone' => $destinationPhone
        ];

        return $this->post("template/send_solapi_message", $inputs);
    }

    public function notifyPromotionRevenueToSlack()
    {
        return $this->post("notify/promotion_revenue_to_slack");
    }

    public function notifyMarketingEmailAgreement($today)
    {
        return $this->post("notify/marketing_email_agreement", ['today' => $today]);
    }

    public function registerAddressBook($userIds)
    {                           
        return $this->post("email/register_address_book", ['user_ids' => $userIds]);
    }
}
