<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyPaymentService extends BaseApiService
{
    const PAYMENT_TYPE_NICEPAY_CREDIT_CARD = 1;
    const PAYMENT_TYPE_ADMIN = 2;
    const PAYMENT_TYPE_BANK_TRANSFER = 3;
    const PAYMENT_TYPE_PAYPAL = 4;
    const PAYMENT_TYPE_NAVERPAY = 5;
    const PAYMENT_TYPE_NAVERPAY_ONETIME = 6;
    const PAYMENT_TYPE_SKILLUP_NAVERPAY = 7;
    const PAYMENT_TYPE_SKILLUP_NICEPAY_CREDIT_CARD = 8;
    const PAYMENT_TYPE_IAMPORT = 90;
    const PAYMENT_TYPE_OLD_ADMIN = 91;

    const PAYMENT_STATUS_WAITING = 1;
    const PAYMENT_STATUS_COMPLETED = 2;
    const PAYMENT_STATUS_FAILED = 3;
    const PAYMENT_STATUS_IN_PROGRESS = 4;
    const PAYMENT_STATUS_REFUNDED = 5;

    const ORDER_STATUS_CHECKEDOUT = 1; // no payment was assigned.
    const ORDER_STATUS_WAITING_PAYMENT = 2; // Reserved Payment. payment can be canceled, changed.
    const ORDER_STATUS_PAID = 3;
    const ORDER_STATUS_CANCELLED = 4;
    const ORDER_STATUS_PROJECT_FAILED = 5;
    const ORDER_STATUS_PAYMENT_IN_PROGRESS = 6; // Right before to make payment from reserved status(user can't change or cancel payment)
    const ORDER_STATUS_PAYMENT_FAILED = 7;
    const ORDER_STATUS_REFUND_REQUESTED = 8;
    const ORDER_STATUS_REFUND_COMPLETED = 9; // requested 단계를 무조건 거치고 이동해야 함
    const ORDER_STATUS_PROJECT_DROP = 10;
    const ORDER_STATUS_CONTENT_RETURNED = 11;
    const ORDER_STATUS_REFUND_FAILED = 12;
    const ORDER_STATUS_MAX = 13;

    const COUPON_V2_STATUS_USABLE = 1;
    const COUPON_V2_STATUS_USED = 2;
    const COUPON_V2_STATUS_EXPIRED = 3;

    const STRING_PAYMENT_TYPE = [
        PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD => "Nicepay 신용카드",
        PublyPaymentService::PAYMENT_TYPE_ADMIN => "관리자 추가",
        PublyPaymentService::PAYMENT_TYPE_BANK_TRANSFER => "계좌이체",
        PublyPaymentService::PAYMENT_TYPE_PAYPAL => "PayPal",
        PublyPaymentService::PAYMENT_TYPE_NAVERPAY => "NaverPay",
        PublyPaymentService::PAYMENT_TYPE_NAVERPAY_ONETIME => "NaverPay 간편결제",
        PublyPaymentService::PAYMENT_TYPE_IAMPORT => "아임포트",
        PublyPaymentService::PAYMENT_TYPE_OLD_ADMIN => "구 관리자 추가",
        PublyPaymentService::PAYMENT_TYPE_SKILLUP_NAVERPAY => "NaverPay (스킬업)",
        PublyPaymentService::PAYMENT_TYPE_SKILLUP_NICEPAY_CREDIT_CARD => "Nicepay 신용카드 (스킬업)",
    ];

    const STRING_PAYMENT_STATUS = [
        PublyPaymentService::PAYMENT_STATUS_WAITING => "예약 완료",
        PublyPaymentService::PAYMENT_STATUS_COMPLETED => "결제성공",
        PublyPaymentService::PAYMENT_STATUS_FAILED => "결제실패",
        PublyPaymentService::PAYMENT_STATUS_IN_PROGRESS => "결제중",
        PublyPayMentService::PAYMENT_STATUS_REFUNDED => "결제환불"
    ];

    const STRING_ORDER_STATUS = [
        PublyPaymentService::ORDER_STATUS_CHECKEDOUT => "주문완료",
        PublyPaymentService::ORDER_STATUS_WAITING_PAYMENT => "결제 대기",
        PublyPaymentService::ORDER_STATUS_PAID => "결제 완료",
        PublyPaymentService::ORDER_STATUS_CANCELLED => "주문 취소",
        PublyPaymentService::ORDER_STATUS_PROJECT_FAILED => "중단",
        PublyPaymentService::ORDER_STATUS_PAYMENT_IN_PROGRESS => "결제중",
        PublyPaymentService::ORDER_STATUS_PAYMENT_FAILED => "결제실패",
        PublyPaymentService::ORDER_STATUS_REFUND_REQUESTED => "환불 신청",
        PublyPaymentService::ORDER_STATUS_REFUND_COMPLETED => "환불 완료",
        PublyPaymentService::ORDER_STATUS_PROJECT_DROP => "프로젝트 중단",
        PublyPaymentService::ORDER_STATUS_CONTENT_RETURNED => "포인트 환급",
        PublyPaymentService::ORDER_STATUS_REFUND_FAILED => "환불 실패"
    ];



    const SUBSCRIPTION_STATUS_INIT = 1;
    const SUBSCRIPTION_STATUS_RENEWED = 2; // 결제 완료
    const SUBSCRIPTION_STATUS_CANCEL_RESERVED = 3; // 결제 취소 예약
    const SUBSCRIPTION_STATUS_FAILED = 4; // 결제 실패
    const SUBSCRIPTION_STATUS_EXPIRED = 5; // 멤버십 기간 만료
    const SUBSCRIPTION_STATUS_CANCEL_COMPLETED = 6; // 결제 취소 완료
    const SUBSCRIPTION_STATUS_IN_PROGRESS = 7;
    const SUBSCRIPTION_STATUS_MAX = 8;

    const SUBSCRIPTION_RENEWAL_HISTORY_STATUS_RENEWED = 1;
    const SUBSCRIPTION_RENEWAL_HISTORY_STATUS_REFUND_REQUESTED = 2;
    const SUBSCRIPTION_RENEWAL_HISTORY_STATUS_REFUND_COMPLETED = 3;
    const SUBSCRIPTION_RENEWAL_HISTORY_STATUS_DUPLICATED = 4;
    const SUBSCRIPTION_RENEWAL_HISTORY_STATUS_REFUND_FAILED = 5;

    const STRING_SUBSCRIPTION_TYPE = [
        PublyPaymentService::SUBSCRIPTION_STATUS_INIT => "초기상태",
        PublyPaymentService::SUBSCRIPTION_STATUS_RENEWED => "구독 중",
        PublyPaymentService::SUBSCRIPTION_STATUS_CANCEL_RESERVED => "취소 신청",
        PublyPaymentService::SUBSCRIPTION_STATUS_FAILED => "결제 실패",
        PublyPaymentService::SUBSCRIPTION_STATUS_EXPIRED => "만료",
        PublyPaymentService::SUBSCRIPTION_STATUS_CANCEL_COMPLETED => "취소 완료",
        PublyPaymentService::SUBSCRIPTION_STATUS_IN_PROGRESS => "결제 중"
    ];

    const STRING_SUBSCRIPTION_RENEWAL_HISTORY_STATUS = [
        PublyPaymentService::SUBSCRIPTION_RENEWAL_HISTORY_STATUS_RENEWED => "결제성공",
        PublyPaymentService::SUBSCRIPTION_RENEWAL_HISTORY_STATUS_REFUND_REQUESTED => "환불 신청",
        PublyPaymentService::SUBSCRIPTION_RENEWAL_HISTORY_STATUS_REFUND_COMPLETED => "환불 완료"
    ];

    const PLAN_TYPE_ONE_MONTH = 1;
    const PLAN_TYPE_SIX_MONTHS = 2;
    const PLAN_TYPE_TWELVE_MONTHS = 3;

    const STRING_PLAN_TYPE = [
        PublyPaymentService::PLAN_TYPE_ONE_MONTH => "1개월마다",
        PublyPaymentService::PLAN_TYPE_SIX_MONTHS => "6개월마다",
        PublyPaymentService::PLAN_TYPE_TWELVE_MONTHS => "12개월마다"
    ];

    const EVENT_CONDITION_ALL = 1;
    const EVENT_CONDITION_SUBSCRIPTION = 2;
    const EVENT_CONDITION_ORDER = 3;
    const EVENT_CONDITION_ORDER_AND_SUBSCRIPTION = 4;
    const EVENT_CONDITION_MAX = 5;

    const STRING_EVENT_CONDITION = [
        PublyPaymentService::EVENT_CONDITION_ALL => "전부(조건없음)",
        PublyPaymentService::EVENT_CONDITION_SUBSCRIPTION => "멤버십 가입자만",
        PublyPaymentService::EVENT_CONDITION_ORDER => "프로젝트 구매자만",
        PublyPaymentService::EVENT_CONDITION_ORDER_AND_SUBSCRIPTION => "프로젝트 구매자 혹은 멤버십 가입자"
    ];

    const ERROR_MESSAGE_EVENT_CONDITION = [
        PublyPaymentService::EVENT_CONDITION_ALL => "",
        PublyPaymentService::EVENT_CONDITION_SUBSCRIPTION => "멤버십 이용자만 구매할 수 있습니다.",
        PublyPaymentService::EVENT_CONDITION_ORDER => "연관 콘텐츠를 예약 구매한 독자만 구매할 수 있습니다.",
        PublyPaymentService::EVENT_CONDITION_ORDER_AND_SUBSCRIPTION => "멤버십 이용자 또는 연관 콘텐츠를 예약 구매한 독자만 구매할 수 있습니다."
    ];

    const POINT_HISTORY_TRANSACTION_TYPE_USED_FOR_PAYMENT = 1;
    const POINT_HISTORY_TRANSACTION_TYPE_ADJUSTED_BY_ADMIN = 2;
    const POINT_HISTORY_TRANSACTION_TYPE_REWORDED_BY_REFERER = 3;
    const POINT_HISTORY_TRANSACTION_TYPE_FAILED_IN_PAYMENT = 4;
    const POINT_HISTORY_TRANSACTION_TYPE_CONTENT_RETURNED = 5;
    const POINT_HISTORY_TRANSACTION_TYPE_PROJECT_SPONSOR = 6;
    const POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_DAILY_VIEW_CONTENT = 7;
    const POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_USER_REFERRAL_SIGNUP = 8;
    const POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_AUTHOR_REFERRAL_SIGNUP = 9;
    const POINT_HISTORY_TRANSACTION_TYPE_APP_INSTALL = 10;
    const POINT_HISTORY_TRANSACTION_TYPE_MAX = 11;

    const STRING_TRANSACTION_TYPE = [
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_USED_FOR_PAYMENT => "포인트 사용",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_ADJUSTED_BY_ADMIN => "어드민 포인트 적립",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_REWORDED_BY_REFERER => "포인트 적립",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_FAILED_IN_PAYMENT => "포인트 결제 취소",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_CONTENT_RETURNED => "콘텐츠 환급 포인트",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_PROJECT_SPONSOR => "후원 하기 포인트",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_DAILY_VIEW_CONTENT => "퍼블리 습관 응원 포인트",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_USER_REFERRAL_SIGNUP => "유저 레퍼럴 회원가입 포인트 적립",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_AUTHOR_REFERRAL_SIGNUP => "저자 레퍼럴 회원가입 포인트 적립",
        PublyPaymentService::POINT_HISTORY_TRANSACTION_TYPE_APP_INSTALL => "앱 설치 축하 포인트 적립"
    ];

    const USER_DEFAULT_PLAN_TYPE_ADMIN = 1;
    const USER_DEFAULT_PLAN_TYPE_REFERRAL = 2;
    const USER_DEFAULT_PLAN_TYPE_CONTENT_RETURN = 3;
    const USER_DEFAULT_PLAN_TYPE_AUTHOR_REFERRAL = 4;

    const STRING_USER_DEFAULT_PLAN_TYPE = [
        PublyPaymentService::USER_DEFAULT_PLAN_TYPE_ADMIN => "관리자 추가",
        PublyPaymentService::USER_DEFAULT_PLAN_TYPE_REFERRAL => "공유 콘텐츠 읽음",
        PublyPaymentService::USER_DEFAULT_PLAN_TYPE_CONTENT_RETURN => "콘텐츠 환급",
        PublyPaymentService::USER_DEFAULT_PLAN_TYPE_AUTHOR_REFERRAL => "저자 공유"
    ];


    const VOUCHER_STATUS_INVITATION_REQUESTED = 1;
    const VOUCHER_STATUS_INVITATION_ACCEPTED = 2;
    const VOUCHER_STATUS_CANCELLED = 3;

    const VOUCHER_USE_HISTORY_STATUS_ACTIVATED = 1;
    const VOUCHER_USE_HISTORY_STATUS_EXPIRED = 2;

    const PAY_WITHOUT_POINT = 0;
    const PAY_WITH_POINT = 1;

    const USE_POINT_ON_SUBSCRIPTION = self::PAY_WITH_POINT;
    const USE_POINT_ON_ORDER = self::PAY_WITHOUT_POINT;
    const USE_POINT_ON_RESERVE = self::PAY_WITHOUT_POINT;
    const USE_POINT_ON_SKILLUP_ORDER = self::PAY_WITH_POINT;

    const NO_PAGE_LIMIT = 0;


    const NICAPAY_ERROR_MESSAGES = [
        "카드번호오류" => "카드번호를 다시 확인해 주세요",
        "유효하지않은 카드번호를 입력하셨습니다. (card_bin 없음)" => "올바른 카드번호를 입력해 주세요",
        "주민OR사업자등록번호오류" => "주민등록번호 또는 사업자등록 번호를 다시 확인해 주세요",
        "비밀번호틀림" => "비밀번호를 다시 확인해 주세요",
        "유효기간오류" => "유효기간을 다시 확인해 주세요",
    ];

    const NAVERPAY_ERROR_MESSAGES = [
        "userCancel" => "결제를 취소하셨습니다. 주문 내용 확인 후 다시 결제해주세요.",
        "OwnerAuthFail" => "타인 명의 카드는 결제가 불가능합니다. 회원 본인 명의의 카드로 결제해주세요.",
        "paymentTimeExpire" => "결제 가능한 시간이 지났습니다. 주문 내용 확인 후 다시 결제해주세요."
    ];

    const NAVERPAY_APPROVAL_TYPES = [
        'ALL' => '전체',
        'APPROVAL' => '승인',
        'CANCEL' => '취소',
        'CANCEL_FAIL' => '취소실패'
    ];

    const NAVERPAY_DIFFERENCE_STATUS_EXIST = 1;
    const NAVERPAY_DIFFERENCE_STATUS_NOT_EXIST = 2;
    const NAVERPAY_DIFFERENCE_STATUS_NO_NEED = 3;

    const NAVERPAY_DIFFERENCE_STATUS = [
        PublyPaymentService::NAVERPAY_DIFFERENCE_STATUS_EXIST => "내역 존재",
        PublyPaymentService::NAVERPAY_DIFFERENCE_STATUS_NOT_EXIST => "내역 미존재",
        PublyPaymentService::NAVERPAY_DIFFERENCE_STATUS_NO_NEED => "해당 없음",
    ];

    const REFUND_HISTORY_STATUS_COMPLETED = 1;
    const REFUND_HISTORY_STATUS_FAILED = 2;

    const NAVERPAY_USED_KEY_MEMBERSHIP = 1;
    const NAVERPAY_USED_KEY_SKILLUP = 2;

    const STRING_NAVERPAY_USED_KEY = [
        PublyPaymentService::NAVERPAY_USED_KEY_MEMBERSHIP => '멤버십',
        PublyPaymentService::NAVERPAY_USED_KEY_SKILLUP => '스킬업',
    ];

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    /*
     * Order related functions
     */
    public function payReservedOrdersByContent($changerId, $contentId)
    {
        return $this->put("order/content/{$contentId}",
            [ 'changer_id' => $changerId,
                'action' => 'pay' ]);
    }

    public function failReservedOrdersByContent($changerId, $contentId)
    {
        return $this->put("order/content/{$contentId}",
            [ 'changer_id' => $changerId,
                'action' => 'fail' ]);
    }

    public function dropOrdersByProject($changerId, $projectId)
    {
        return $this->put("order/content/{$projectId}",
            [ 'changer_id' => $changerId,
                'action' => 'drop' ]);
    }

    public function getOrders($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("order", $filterArray);
    }

    public function getOrdersByIds($orderIds, $filterArray = [])
    {
        $filterArray['order_ids'] = implode(',', $orderIds);
        return $this->get("order/order_ids", $filterArray);
    }

    public function getOrdersByUser($userId, $page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("order/user/{$userId}", $filterArray);
    }

    public function getOrder($orderId, $filterArray = [])
    {
        return $this->get("order/{$orderId}", $filterArray);
    }

    public function getOrderByUserIdAndProjectId($userId, $projectId, $filterArray = [])
    {
        return $this->get("order/user/{$userId}/project/{$projectId}", $filterArray);
    }

    public function deleteOrder(
        $changerId,
        $orderId)
    {
        return $this->post("order/{$orderId}/delete",
            ['changer_id' => $changerId]);
    }

    public function getTotalOrdersByRewardId($rewardId, $filterArray = [])
    {
        return $this->get("order/reward/{$rewardId}/total", $filterArray);
    }

    public function getTotalOrdersByEventId($eventId, $filterArray = [])
    {
        return $this->get("order/event/{$eventId}/total", $filterArray);
    }

    public function getTotalOrdersByProject($projectId, $filterArray = [])
    {
        return $this->get("order/project/{$projectId}/total", $filterArray);
    }

    public function getOrdersByProjectId($projectId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("order/project/{$projectId}", $filterArray);
    }

    public function getOrdersByEventId($eventId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("order/event/{$eventId}", $filterArray);
    }

    public function getOrdersByRewardIds($rewardIds, $filterArray = [])
    {
        $filterArray['reward_ids'] = implode(',', $rewardIds);
        return $this->get("order/reward_ids", $filterArray);
    }

    public function getOrdersByProjectIds($projectIds, $filterArray = [])
    {
        $filterArray['project_ids'] = implode(',', $projectIds);
        return $this->get("order/project_ids", $filterArray);
    }

    public function getOrdersByEventIds($eventIds, $filterArray = [])
    {
        $filterArray['event_ids'] = implode(',', $eventIds);
        return $this->get("order/event_ids", $filterArray);
    }

    public function order(
        $userId,
        $contentId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $userId,
                'content_id' => $contentId,
                'reward_id' => $rewardId,
                'user_id' => $userId,
                'price' => $price,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'user_phone' => $userPhone ];
            if ($deliveryName || $deliveryPhone || $deliveryZipcode || $deliveryAddress) {
                $inputs = array_merge($inputs, [ 'delivery_name' => $deliveryName,
                    'delivery_phone' => $deliveryPhone,
                    'delivery_zipcode' => $deliveryZipcode,
                    'delivery_address' => $deliveryAddress,
                ]);
            }
            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultOrder['success']['data'];

        return $result;
    }

    public function order2(
        $changerId,
        $userId,
        $contentId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $changerId,
                'content_id' => $contentId,
                'reward_id' => $rewardId,
                'user_id' => $userId,
                'price' => $price,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'user_phone' => $userPhone ];
            if ($deliveryName || $deliveryPhone || $deliveryZipcode || $deliveryAddress) {
                $inputs = array_merge($inputs, [ 'delivery_name' => $deliveryName,
                    'delivery_phone' => $deliveryPhone,
                    'delivery_zipcode' => $deliveryZipcode,
                    'delivery_address' => $deliveryAddress,
                ]);
            }

            if (is_null($isPreorder) == false) {
                $inputs = array_merge($inputs, [ 'is_preorder' => $isPreorder ]);
            }

            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultOrder['success']['data'];

        return $result;
    }

    public function order3(
        $changerId,
        $userId,
        $contentId,
        $rewardId,
        $price,
        $usingSubscriptionReward,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $changerId,
                'content_id' => $contentId,
                'reward_id' => $rewardId,
                'user_id' => $userId,
                'price' => $price,
                'is_subscription' => $usingSubscriptionReward,
                'user_name' => $userName,
                'user_email' => $userEmail,
                'user_phone' => $userPhone ];
            if ($deliveryName || $deliveryPhone || $deliveryZipcode || $deliveryAddress) {
                $inputs = array_merge($inputs, [ 'delivery_name' => $deliveryName,
                    'delivery_phone' => $deliveryPhone,
                    'delivery_zipcode' => $deliveryZipcode,
                    'delivery_address' => $deliveryAddress,
                ]);
            }

            if (is_null($isPreorder) == false) {
                $inputs = array_merge($inputs, [ 'is_preorder' => $isPreorder ]);
            }

            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultOrder['success']['data'];

        return $result;
    }

    /*
     * Helpers
     */
    public function orderAndReservePayment(
        $userId,
        $creditCardId,
        $contentId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null)
    {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order(
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];

        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->reservePayment(
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            $creditCardId);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];
        //

        $result['success'] = true;
        $result['order'] = $order;
        return $result;
    }

    public function addCreditCardAndOrderAndReservePayment(
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $contentId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard(
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        // order
        $resultOrder = $this->order(
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->reservePayment(
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            $creditCard['id']);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];
        //

        $result['success'] = true;
        $result['order'] = $order;
        $result['creditCard'] = $creditCard;
        return $result;
    }

    public function orderAndReservePayment2(
        $changerId,
        $userId,
        $creditCardId,
        $contentId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->reservePayment2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            $creditCardId);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];
        //

        $result['success'] = true;
        $result['order'] = $order;
        return $result;
    }

    public function orderAndReservePayment3(
        $changerId,
        $userId,
        $creditCardId,
        $contentId,
        $rewardId,
        $price,
        $usingSubscriptionReward,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order3(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $usingSubscriptionReward,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->reservePayment2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            $creditCardId);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $result['success'] = true;
        $result['order'] = $order;
        return $result;
    }

    public function orderAndPayEvent(
        $changerId,
        $userId,
        $creditCardId,
        $price,
        $eventId)
    {
        $result = [ 'success' => false ];

        try {
            $inputs = [ 'changer_id' => $changerId,
                'event_id' => $eventId,
                'user_id' => $userId,
                'price' => $price
            ];

            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
            return $result;
        }

        // order
        $order = $resultOrder['success']['data'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->pay2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCardId,
            true,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        return $result;
    }

    public function addCreditCardAndOrderAndPayEvent(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $price,
        $eventId)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        $result['creditCard'] = $creditCard;
        // 정상적으로 카드 등록 되었음.

        try {
            $inputs = [ 'changer_id' => $changerId,
                'event_id' => $eventId,
                'user_id' => $userId,
                'price' => $price
            ];

            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
            return $result;
        }

        // order
        $order = $resultOrder['success']['data'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->pay2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCard['id'],
            true,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        $result['creditCard'] = $creditCard;
        return $result;
    }

    public function orderAndPay(
        $changerId,
        $userId,
        $creditCardId,
        $contentId,
        $rewardId,
        $price,
        $installmentMonth,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->pay3(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCardId,
            true,
            $installmentMonth,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        return $result;
    }

    public function orderAndPaySkillup(
        $changerId,
        $userId,
        $creditCardId,
        $contentId,
        $rewardId,
        $price,
        $installmentMonth,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->paySkillup(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_SKILLUP_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCardId,
            true,
            $installmentMonth,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        return $result;
    }

    public function orderAndPayByNaverpay(
        $changerId,
        $userId,
        $naverpayOnetimeId,
        $projectId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $isPreorder
    ) {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $projectId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            null,
            null,
            null,
            null,
            $isPreorder
        );

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->payByNaverpaySimple(
            $changerId,
            $userId,
            $order['id'],
            $naverpayOnetimeId,
            true,
            ''
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        return $result;
    }

    public function orderAndPayBySkillupNaverpay(
        $changerId,
        $userId,
        $naverpayOnetimeId,
        $projectId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $isPreorder
    ) {
        $result = [ 'success' => false ];

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $projectId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            null,
            null,
            null,
            null,
            $isPreorder
        );

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->payBySkillupNaverpay(
            $changerId,
            $userId,
            $order['id'],
            $naverpayOnetimeId,
            true,
            ''
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        return $result;
    }

    public function addCreditCardAndOrderAndPay(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $contentId,
        $rewardId,
        $price,
        $installmentMonth,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->pay3(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCard['id'],
            true,
            $installmentMonth,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        $result['creditCard'] = $creditCard;
        return $result;
    }

    public function addCreditCardAndOrderAndPaySkillup(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $contentId,
        $rewardId,
        $price,
        $installmentMonth,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->paySkillup(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_SKILLUP_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCard['id'],
            true,
            $installmentMonth,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        $result['creditCard'] = $creditCard;
        return $result;
    }

    public function addCreditCardAndOrderAndReservePayment2(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $contentId,
        $rewardId,
        $price,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        // order
        $resultOrder = $this->order2(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->reservePayment2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            $creditCard['id']);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];
        //

        $result['success'] = true;
        $result['order'] = $order;
        $result['creditCard'] = $creditCard;
        return $result;
    }

    public function addCreditCardAndOrderAndReservePayment3(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $contentId,
        $rewardId,
        $price,
        $usingSubscriptionReward,
        $userName,
        $userEmail,
        $userPhone,
        $deliveryName = null,
        $deliveryPhone = null,
        $deliveryZipcode = null,
        $deliveryAddress = null,
        $isPreorder = null)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        // order
        $resultOrder = $this->order3(
            $changerId,
            $userId,
            $contentId,
            $rewardId,
            $price,
            $usingSubscriptionReward,
            $userName,
            $userEmail,
            $userPhone,
            $deliveryName,
            $deliveryPhone,
            $deliveryZipcode,
            $deliveryAddress,
            $isPreorder);

        if (!$resultOrder['success']) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $resultOrder['error_code'];
            $result['message'] = $resultOrder['message'];
            return $result;
        }

        $order = $resultOrder['item'];
        // 정상적으로 주문 되었음.

        // reserve payment
        $resultPayment = $this->reservePayment2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            $creditCard['id']);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];
        //

        $result['success'] = true;
        $result['order'] = $order;
        $result['creditCard'] = $creditCard;
        return $result;
    }

    /*
     * Payment related functions
     */
    public function reservePayment(
        $userId,
        $orderId,
        $pgType,
        $creditCardId)
    {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $userId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'pg_type' => $pgType,
                    'credit_card_id' => $creditCardId,
                    'immediate' => false,
                    'use_point' => static::USE_POINT_ON_RESERVE
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }

    public function reservePayment2(
        $changerId,
        $userId,
        $orderId,
        $pgType,
        $creditCardId)
    {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'pg_type' => $pgType,
                    'credit_card_id' => $creditCardId,
                    'immediate' => false,
                    'use_point' => static::USE_POINT_ON_RESERVE
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }

    /*
     * Payment related functions
     */
    public function pay2(
        $changerId,
        $userId,
        $orderId,
        $pgType,
        $paymentMethodIdName,
        $paymentMethodId,
        $immediate,
        $note
    ) {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'pg_type' => $pgType,
                    $paymentMethodIdName => $paymentMethodId,
                    'immediate' => $immediate,
                    'note' => $note,
                    'use_point' => static::USE_POINT_ON_ORDER
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }

    public function pay3(
        $changerId,
        $userId,
        $orderId,
        $pgType,
        $paymentMethodIdName,
        $paymentMethodId,
        $immediate,
        $installmentMonth,
        $note
    ) {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'pg_type' => $pgType,
                    $paymentMethodIdName => $paymentMethodId,
                    'immediate' => $immediate,
                    'note' => $note,
                    'use_point' => static::USE_POINT_ON_ORDER,
                    'installment_month' => $installmentMonth
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }

    public function paySkillup(
        $changerId,
        $userId,
        $orderId,
        $pgType,
        $paymentMethodIdName,
        $paymentMethodId,
        $immediate,
        $installmentMonth,
        $note
    ) {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'pg_type' => $pgType,
                    $paymentMethodIdName => $paymentMethodId,
                    'immediate' => $immediate,
                    'note' => $note,
                    'use_point' => static::USE_POINT_ON_SKILLUP_ORDER,
                    'installment_month' => $installmentMonth
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }


    public function payByNaverpaySimple(
        $changerId,
        $userId,
        $orderId,
        $naverpayOnetimeId,
        $immediate,
        $note = ''
    ) {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'naverpay_onetime_id' => $naverpayOnetimeId,
                    'pg_type' => static::PAYMENT_TYPE_NAVERPAY_ONETIME,
                    'immediate' => $immediate,
                    'note' => $note,
                    'use_point' => static::USE_POINT_ON_ORDER
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }


    public function payBySkillupNaverpay(
        $changerId,
        $userId,
        $orderId,
        $naverpayOnetimeId,
        $immediate,
        $note = ''
    ) {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'naverpay_onetime_id' => $naverpayOnetimeId,
                    'pg_type' => static::PAYMENT_TYPE_SKILLUP_NAVERPAY,
                    'immediate' => $immediate,
                    'note' => $note,
                    'use_point' => static::USE_POINT_ON_SKILLUP_ORDER
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }

    /*
     * Payment Methods related functions
     */

    public function getCreditCard($creditCardId)
    {
        return $this->get("credit_card/{$creditCardId}");
    }

    public function getCreditCardsByUser($userId)
    {
        return $this->get("credit_card/user/{$userId}");
    }

    public function getBankTransfer($bankTransferId)
    {
        return $this->get("bank_transfer/{$bankTransferId}");
    }

    public function getBankTransferByUser($userId, $filterArray = [])
    {
        return $this->get("bank_transfer/user/{$userId}", $filterArray);
    }

    public function getNaverpaysByUser($userId)
    {
        return $this->get("naverpay/user/{$userId}");
    }

    public function addCreditCard(
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password)
    {
        $result = [ 'success' => false ];
        try {
            $resultCreditCard =
                $this->post('credit_card', [
                    'changer_id' => $userId,
                    'user_id' => $userId,
                    'card_number' => $creditCardNumber,
                    'expire_year' => $expireYear,
                    'expire_month' => $expireMonth,
                    'id' => $id,
                    'password' => $password
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultCreditCard['success']['data'];

        return $result;
    }

    public function addCreditCard2(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password)
    {
        $result = [ 'success' => false ];
        try {
            $resultCreditCard =
                $this->post('credit_card', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'card_number' => $creditCardNumber,
                    'expire_year' => $expireYear,
                    'expire_month' => $expireMonth,
                    'id' => $id,
                    'password' => $password
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultCreditCard['success']['data'];

        return $result;
    }

    public function deleteCreditCard(
        $userId,
        $creditCardId)
    {
        $result = [ 'success' => false ];
        try {
            $resultCreditCard =
                $this->post("credit_card/{$creditCardId}/delete", [
                    'changer_id' => $userId,
                    'user_id' => $userId
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        return $result;
    }

    public function deleteNaverpay(
        $userId,
        $naverpayId
    ) {
        $result = [ 'success' => false ];
        try {
            $resultNaverpay =
                $this->post("naverpay/{$naverpayId}/delete", [
                    'changer_id' => $userId,
                    'user_id' => $userId
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'naverpay';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        return $result;
    }

    public function deleteNaverpayByAdmin(
        $changerId,
        $userId,
        $naverpayId
    ) {
        $result = [ 'success' => false ];
        try {
            $resultNaverpay =
                $this->post("naverpay/{$naverpayId}/delete", [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'force' => true
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'naverpay';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        return $result;
    }

    public function addBankTransfer($changerId, $userId, $name)
    {
        $result['success'] = false;
        try {
            $inputs = [
                'changer_id' => $changerId,
                'user_id' => $userId,
                'name' => $name
            ];
            $bankTransferResult = $this->post("bank_transfer", $inputs);
        } catch (\Exception $e) {
            $result['success'] = false;
            $result['from'] = 'bank_transfer';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['item'] = $bankTransferResult['success']['data'];
        $result['success'] = true;
        return $result;
    }

    /*
     * Main Payment Method related functions
     */
    public function getMainPaymentMethod($userId)
    {
        $pgType = static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD;
        try {
            $resultMainPaymentMethod =
                $this->get("user_main_payment_method/user/{$userId}/pg_type/{$pgType}");
        } catch (ResponseException $e) {
            return null;
        }

        return $resultMainPaymentMethod['success']['data'];
    }

    public function setMainPaymentMethod($userId, $creditCardId)
    {
        $result = [ 'success' => false ];
        $pgType = static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD;
        try {
            $resultMainPaymentMethod =
                $this->put("user_main_payment_method/user/{$userId}/pg_type/{$pgType}",
                    [ 'credit_card_id' => $creditCardId ]);
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function setMainPaymentMethod2($userId, $methodId, $methodIdName)
    {
        $result = [ 'success' => false ];
        $pgType = null;

        if ($methodIdName == 'credit_card_id') {
            $pgType = static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD;
        } else if ($methodIdName == 'naverpay_id') {
            $pgType = static::PAYMENT_TYPE_NAVERPAY;
        }

        try {
            $this->put(
                "user_main_payment_method/user/{$userId}/pg_type/{$pgType}",
                [ $methodIdName => $methodId ]
            );
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function retryPayment($changerId, $paymentId)
    {
        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        return $this->put("/payment/{$paymentId}", $inputs);
    }

    public function updatePayment($changerId, $paymentId, $inputs)
    {
        $inputs['changer_id'] = $changerId;

        $result = [ 'success' => false ];
        try {
            $resultApi = $this->put("/payment/{$paymentId}", $inputs);
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function updateOrder($changerId,
                                $orderId,
                                $userName,
                                $userEmail,
                                $userPhone,
                                $deliveryName = null,
                                $deliveryPhone = null,
                                $deliveryZipcode = null,
                                $deliveryAddress = null,
                                $note,
                                $force = false)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $changerId,
                'force' => $force ? 1 : 0,
                'action' => 'modify',
                'user_name' => $userName,
                'user_email' => $userEmail,
                'user_phone' => $userPhone,
                'note' => $note ];
            if ($deliveryName || $deliveryPhone || $deliveryZipcode || $deliveryAddress) {
                $inputs = array_merge($inputs, [ 'delivery_name' => $deliveryName,
                    'delivery_phone' => $deliveryPhone,
                    'delivery_zipcode' => $deliveryZipcode,
                    'delivery_address' => $deliveryAddress,
                ]);
            }

            $resultApi = $this->put("/order/{$orderId}", $inputs);
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function cancelOrder($changerId, $orderId, $force = false)
    {
        $result = [ 'success' => false ];
        try {
            $resultApi =
                $this->put("/order/{$orderId}",
                    [ 'changer_id' => $changerId,
                        'action' => 'cancel',
                        'force' => $force ? 1 : 0 ]);
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function cancelOrder2($changerId, $orderId, $force = false)
    {
        return $this->put("/order/{$orderId}",
            [ 'changer_id' => $changerId,
                'action' => 'cancel',
                'force' => $force ? 1 : 0 ]);
    }

    public function requestRefundOrder($changerId, $orderId, $force = false)
    {
        return $this->put("/order/{$orderId}",
            [ 'changer_id' => $changerId,
                'action' => 'request-refund',
                'force' => $force ? 1 : 0 ]);
    }

    public function completeRefundOrder($changerId, $orderId, $force = false)
    {// only for admin. do not use this interface on www.
        return $this->put("/order/{$orderId}",
            [ 'changer_id' => $changerId,
                'action' => 'complete-refund',
                'force' => $force ? 1 : 0 ]);
    }

    public function returnContent($changerId, $orderId, $force = false)
    {
        return $this->put("/order/{$orderId}",
            [ 'changer_id' => $changerId,
                'action' => 'return-content',
                'force' => $force ? 1 : 0 ]);
    }

    public function returnContents($changerId, $orderIds)
    {
        $inputs = [];
        $inputs['order_ids'] = implode(',', $orderIds);
        $inputs['changer_id'] = $changerId;

        return $this->post("/order/contents_return", $inputs);
    }

    public function refreshSetReaderByProject($projectId)
    {
        return $this->post("/order/refresh_set_reader", ['project_id' => $projectId]);
    }

    public function getOrderCommentsByProjectId($projectId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("order_comment/project/{$projectId}", $filterArray);
    }

    public function addOrderComment($changerId, $orderId, $comment)
    {
        return $this->post("/order_comment/", [ 'changer_id' => $changerId,
            'order_id' => $orderId,
            'comment' => $comment ]);
    }

    public function subscriptionAndPay3($changerId, $userId, $paymentMethodId, $paymentMethodIdName, $pgType, $planId, $price, $installmentMonth, $useReferralPlanIfPossible)
    {
        $result = [ 'success' => false ];

        // subscription
        $resultSubscription = $this->subscription2(
            $changerId,
            $userId,
            $planId,
            $price,
            $useReferralPlanIfPossible
        );

        if (!$resultSubscription['success']) {
            $result['success'] = false;
            $result['from'] = 'subscription';
            $result['error_code'] = $resultSubscription['error_code'];
            $result['message'] = $resultSubscription['message'];
            return $result;
        }

        $subscription = $resultSubscription['item'];
        $result['subscription'] = $subscription;

        // payment
        $resultPayment = $this->paySubscription(
            $changerId,
            $userId,
            $subscription['id'],
            $pgType,
            $paymentMethodIdName,
            $paymentMethodId,
            //            true,
            '',
            $installmentMonth
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['item'];

        $subscriptionResult = $this->get("subscription/{$subscription['id']}");
        $subscription = $subscriptionResult['success']['data'];

        $result['success'] = true;
        $result['subscription'] = $subscription;
        $result['payment'] = $payment;

        return $result;
    }

    public function addNaverpay(
        $userId,
        $reserveId,
        $tempReceiptId,
        $totalPayAmount
    ) {
        $result = [ 'success' => false ];
        try {
            $resultNaverpay =
                $this->post('naverpay', [
                    'changer_id' => $userId,
                    'user_id' => $userId,
                    'reserve_id' => $reserveId,
                    'temp_receipt_id' => $tempReceiptId,
                    'total_pay_amount' => $totalPayAmount
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'naverpay';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultNaverpay['success']['data'];

        return $result;
    }

    public function addNaverpayAndSubscriptionAndPay(
        $changerId,
        $userId,
        $reserveId,
        $tempReceiptId,
        $planId,
        $price,
        $useReferralPlanIfPossible
    ) {
        $result = [ 'success' => false ];

        // add naverpay
        $resultNaverpay = $this->addNaverpay(
            $userId,
            $reserveId,
            $tempReceiptId,
            $price
        );

        if (!$resultNaverpay['success']) {
            $result['success'] = false;
            $result['from'] = 'naverpay';
            $result['error_code'] = $resultNaverpay['error_code'];
            $result['message'] = $resultNaverpay['message'];
            return $result;
        }

        $naverpay = $resultNaverpay['item'];
        $naverpayId = $naverpay['id'];
        $result['naverpay'] = $naverpay;

        // subscription
        $resultSubscription = $this->subscription2(
            $changerId,
            $userId,
            $planId,
            $price,
            $useReferralPlanIfPossible
        );

        if (!$resultSubscription['success']) {
            $result['success'] = false;
            $result['from'] = 'subscription';
            $result['error_code'] = $resultSubscription['error_code'];
            $result['message'] = $resultSubscription['message'];
            return $result;
        }

        $subscription = $resultSubscription['item'];
        $result['subscription'] = $subscription;

        // payment
        $resultPayment = $this->paySubscription(
            $changerId,
            $userId,
            $subscription['id'],
            static::PAYMENT_TYPE_NAVERPAY,
            'naverpay_id',
            $naverpayId,
            //            true,
            ''
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }
        $payment = $resultPayment['item'];
        $result['payment'] = $payment;

        $subscriptionResult = $this->get("subscription/{$subscription['id']}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;

        return $result;
    }

    public function addCreditCardAndSubscriptionAndPay2(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $planId,
        $price,
        $installmentMonth,
        $useReferralPlanIfPossible
    ) {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard(
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        $creditCardId = $creditCard['id'];
        $result['creditCard'] = $creditCard;

        // subscription
        $resultSubscription= $this->subscription2(
            $changerId,
            $userId,
            $planId,
            $price,
            $useReferralPlanIfPossible
        );

        if (!$resultSubscription['success']) {
            $result['success'] = false;
            $result['from'] = 'subscription';
            $result['error_code'] = $resultSubscription['error_code'];
            $result['message'] = $resultSubscription['message'];
            return $result;
        }

        $subscription = $resultSubscription['item'];
        $result['subscription'] = $subscription;

        // payment
        $resultPayment = $this->paySubscription(
            $changerId,
            $userId,
            $subscription['id'],
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCardId,
            //            true,
            '',
            $installmentMonth
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['item'];
        $result['payment'] = $payment;

        $subscriptionResult = $this->get("subscription/{$subscription['id']}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function deleteSubscription(
        $changerId,
        $subscriptionId)
    {
        try {
            $this->post("subscription/{$subscriptionId}/delete", ['changer_id' => $changerId]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            return $result;
        }

        $result['success'] = true;
        return $result;
    }

    public function subscription(
        $changerId,
        $userId,
        $planId,
        $price)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $changerId,
                'user_id' => $userId,
                'plan_id' => $planId,
                'price' => $price ];

            $resultSubscription = $this->post('subscription', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultSubscription['success']['data'];

        return $result;
    }

    public function subscription2(
        $changerId,
        $userId,
        $planId,
        $price,
        $useReferralPlanIfPossible)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $changerId,
                'user_id' => $userId,
                'plan_id' => $planId,
                'price' => $price,
                'use_referral_plan_if_possible'=> $useReferralPlanIfPossible
            ];

            $resultSubscription = $this->post('subscription', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultSubscription['success']['data'];

        return $result;
    }

    public function paySubscription(
        $changerId,
        $userId,
        $subscriptionId,
        $pgType,
        $paymentMethodIdName,
        $paymentMethodId,
        $note,
        $installmentMonth = null
    ) {
        $result = [ 'success' => false ];
        try {
            $resultPayment =
                $this->post('payment', [
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'subscription_id' => $subscriptionId,
                    'pg_type' => $pgType,
                    $paymentMethodIdName => $paymentMethodId,
                    'immediate' => true,
                    'note' => $note,
                    'use_point' => static::USE_POINT_ON_SUBSCRIPTION,
                    'installment_month' => $installmentMonth
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $result['success'] = true;
        $result['item'] = $resultPayment['success']['data'];

        return $result;
    }

    public function changeCardAndResumeSubscription(
        $changerId,
        $paymentId,
        $subscriptionId,
        $creditCardId,
        $force = false)
    {

        $resultPayment = $this->updatePayment($changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
                'credit_card_id' => $creditCardId
            ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        try {
            $resultSubscription = $this->resumeSubscription($changerId, $subscriptionId, $force);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'subscription';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function changePaymentMethodAndResumeSubscription(
        $changerId,
        $paymentId,
        $subscriptionId,
        $paymentMethodId,
        $paymentMethodIdName,
        $installmentMonth,
        $force = false
    ) {

        $pgType = null;
        if ($paymentMethodIdName == 'credit_card_id') {
            $pgType = PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD;
        } else if ($paymentMethodIdName == 'naverpay_id') {
            $pgType = PublyPaymentService::PAYMENT_TYPE_NAVERPAY;
        }

        $resultPayment = $this->updatePayment(
            $changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => $pgType,
                $paymentMethodIdName => $paymentMethodId,
                'installment_month' => $installmentMonth
            ]
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        try {
            $resultSubscription = $this->resumeSubscription($changerId, $subscriptionId, $force);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'subscription';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function addCreditCardAndResumeSubscription(
        $changerId,
        $userId,
        $paymentId,
        $subscriptionId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $force = false)
    {
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        $resultPayment = $this->updatePayment($changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
                'credit_card_id' => $creditCard['id']
            ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        try {
            $resultSubscription = $this->resumeSubscription($changerId, $subscriptionId, $force);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'subscription';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];

            return $result;
        }

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function resumeSubscription($changerId, $subscriptionId, $force = false)
    {
        return $this->put(
            "/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'resume',
                'force' => $force ? 1 : 0 ]
        );
    }

    public function changeCardAndRecoverSubscription($changerId,
        $subscriptionId,
        $paymentId,
        $creditCardId,
        $force = false)
    {
        $resultPayment = $this->updatePayment($changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
                'credit_card_id' => $creditCardId
            ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        $resultPayment = $this->put("/payment/{$paymentId}", $inputs);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['success']['data'];
        $result['payment'] = $payment;

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function changeCardAndRecoverSubscription2(
        $changerId,
        $subscriptionId,
        $paymentId,
        $paymentMethodId,
        $paymentMethodIdName,
        $installmentMonth,
        $force = false
    ) {
        $pgType = null;
        if ($paymentMethodIdName == 'credit_card_id') {
            $pgType = PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD;
        } else if ($paymentMethodIdName == 'naverpay_id') {
            $pgType = PublyPaymentService::PAYMENT_TYPE_NAVERPAY;
        }

        $resultPayment = $this->updatePayment(
            $changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => $pgType,
                $paymentMethodIdName => $paymentMethodId,
                'installment_month' => $installmentMonth
            ]
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        $resultPayment = $this->put("/payment/{$paymentId}", $inputs);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['success']['data'];
        $result['payment'] = $payment;

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function changeNaverpayAndRecoverSubscription(
        $changerId,
        $subscriptionId,
        $paymentId,
        $naverpayId,
        $force = false
    ) {
        $resultPayment = $this->updatePayment(
            $changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NAVERPAY,
                'naverpay_id' => $naverpayId
            ]
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        $resultPayment = $this->put("/payment/{$paymentId}", $inputs);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['success']['data'];
        $result['payment'] = $payment;

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function addCreditCardAndRecoverSubscription(
        $changerId,
        $userId,
        $subscriptionId,
        $paymentId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $installmentMonth,
        $force = false
    ) {
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        $result['creditCard'] = $creditCard;
        // 정상적으로 카드 등록 되었음.

        $resultPayment = $this->updatePayment($changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
                'credit_card_id' => $creditCard['id'],
                'installment_month' => $installmentMonth
            ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        $resultPayment = $this->put("/payment/{$paymentId}", $inputs);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['success']['data'];
        $result['payment'] = $payment;

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function recoverSubscription($changerId, $subscriptionId, $paymentId, $force = false)
    {
        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        return $this->put("/payment/{$paymentId}", $inputs);
    }

    public function expireSubscription($changerId, $subscriptionId, $force = false)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'expire',
                'force' => $force ? 1 : 0 ]);
    }

    public function renewalSubscription($changerId, $subscriptionId, $paymentId, $force = false)
    {
        $inputs = [];
        $inputs['changer_id'] = $changerId;
        $inputs['action'] = 'pay';

        return $this->put("/payment/{$paymentId}", $inputs);
    }

    public function resetSubscriptionRenewAt($changerId, $subscriptionId, $force = false)
    {
        return $this->put("subscription/{$subscriptionId}/", ['changer_id' => $changerId,
            'action' => 'expired',
            'force' => $force ? 1 : 0]);
    }

    public function cancelSubscriptionReserved($changerId, $days)
    {
        return $this->put("/subscription/cancel_subscription_reserved",
            ['changer_id' => $changerId,
                'renew_day' => implode(',', $days)]);
    }

    public function renewSubscriptions($changerId, $days)
    {
        return $this->put("/subscription/renew_subscriptions",
            ['changer_id' => $changerId,
                'renew_day' => implode(',', $days)]);
    }

    public function expireSubscriptions($changerId)
    {
        return $this->put(
            "/subscription/expire_subscriptions",
            [
                'changer_id' => $changerId
            ]);
    }

    public function updateSubscription($changerId, $subscriptionId, $note, $force = false)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'update',
                'note' => $note,
                'force' => $force ? 1 : 0
            ]);
    }

    // deprecated: SubscriptionController@www-l5 is using this
    public function changeSubscriptionPlanId($changerId, $subscriptionId, $planId)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'change_plan',
                'plan_id' => $planId ]);
    }

    public function changeSubscriptionNextPlan($changerId, $subscriptionId, $plan_id, $force = false)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'change_plan',
                'plan_id'=> $plan_id,
                'force' => $force ? 1 : 0
            ]);
    }

    public function getSubscriptions($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("subscription/", $filterArray);
    }

    public function getSubscription($subscriptionId)
    {
        return $this->get("subscription/{$subscriptionId}");
    }

    public function getSubscriptionByUser($userId)
    {
        return $this->get("subscription/user/{$userId}");
    }

    public function getSubscriptionsByRenewDays($renewDays, $filterArray = [])
    {
        $filterArray['renew_day_in'] = implode(',', $renewDays);
        return $this->get("subscription/renew_days/", $filterArray);
    }

    public function cancelSubscription($changerId, $subscriptionId, $force = false)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'cancel',
                'force' => $force ? 1 : 0 ]);
    }

    public function getSubscriptionRenewalHistoriesBySubscription($subscriptionId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        $filterArray['subscription_id'] = $subscriptionId;
        return $this->get("subscription_renewal_history", $filterArray);
    }

    public function getSubscriptionRenewalHistoriesBySubscription2($subscriptionId, $filterArray = [])
    {
        return $this->get("subscription_renewal_history/subscription/{$subscriptionId}", $filterArray);
    }

    public function getSubscriptionRenewalHistoriesBySettlement($settlementYear, $settlementMonth, $filterArray = [])
    {
        return $this->get("subscription_renewal_history/settlement_year/{$settlementYear}/settlement_month/{$settlementMonth}", $filterArray);
    }

    public function getSubscriptionRenewalHistory($subscriptionRenewalHistoryId)
    {
        return $this->get("subscription_renewal_history/{$subscriptionRenewalHistoryId}");
    }

    // deprecated
    public function getSubscriptionRenewalHistories($userId, $settlementYear, $settlementMonth, $filterArray = [])
    {
        $filterArray['user_id'] = $userId;
        $filterArray['settlement_year'] = $settlementYear;
        $filterArray['settlement_month'] = $settlementMonth;
        return $this->get("subscription_renewal_history", $filterArray);
    }

    public function requestRefundSubscriptionRenewalHistory($changerId,
                                                            $subscriptionId,
                                                            $subscriptionRenewalHistoryId,
                                                            $force = false)
    {
        return $this->put("subscription_renewal_history/{$subscriptionRenewalHistoryId}",
            ['changer_id' => $changerId,
                'subscription_id' => $subscriptionId,
                'action' => 'request-refund',
                'force' => $force ? 1 : 0]);
    }

    public function completeRefundSubscriptionRenewalHistory($changerId,
                                                             $subscriptionId,
                                                             $subscriptionRenewalHistoryId,
                                                             $force = false)
    {
        return $this->put("subscription_renewal_history/{$subscriptionRenewalHistoryId}",
            ['changer_id' => $changerId,
                'subscription_id' => $subscriptionId,
                'action' => 'complete-refund',
                'force' => $force ? 1 : 0]);
    }

    public function existsSubscriptionByUserId($userId)
    {
        return $this->get("subscription/user/{$userId}/exists");
    }

    public function getPlans($filterArray = [])
    {
        return $this->get("plan", $filterArray);
    }

    public function getPlansByIds($planIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $planIds);
        return $this->get("plan/plan_ids", $filterArray);
    }

    public function getDefaultPlan()
    {
        return $this->get("plan/default");
    }

    public function getPlan($planId)
    {
        return $this->get("plan/{$planId}");
    }

    public function getPlansByNextPlanId($planId)
    {
        return $this->get("plan/next_plan/{$planId}");
    }

    public function changeSubscriptionCreditCard($changerId, $paymentId, $creditCardId)
    {
        $resultPayment = $this->updatePayment($changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
                'credit_card_id' => $creditCardId
            ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $result['success'] = true;
        return $result;
    }

    public function changeSubscriptionPaymentMethod($changerId, $paymentId, $paymentMethodId, $paymentMethodIdName, $installmentMonth)
    {
        $pgType = null;
        if ($paymentMethodIdName == 'credit_card_id') {
            $pgType = PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD;
        } else if ($paymentMethodIdName == 'naverpay_id') {
            $pgType = PublyPaymentService::PAYMENT_TYPE_NAVERPAY;
        }

        $resultPayment = $this->updatePayment(
            $changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => $pgType,
                $paymentMethodIdName => $paymentMethodId,
                'installment_month' => $installmentMonth
            ]
        );

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $result['success'] = true;
        return $result;
    }

    public function addCreditCardAndChangeSubscriptionCreditCard(
        $changerId,
        $userId,
        $paymentId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $installmentMonth
    ) {
        $resultCreditCard = $this->addCreditCard2(
            $changerId,
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        $resultPayment = $this->updatePayment($changerId,
            $paymentId,
            [
                'action' => 'change_payment_method',
                'pg_type' => PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
                'credit_card_id' => $creditCard['id'],
                'installment_month' => $installmentMonth
            ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $result['success'] = true;
        $result['credit_card'] = $creditCard;
        return $result;
    }

    public function createCoupon($changerId,
                                 $code,
                                 $description,
                                 $quantity,
                                 $durationMinutes,
                                 $expireAt,
                                 $settlementPrice)
    {
        return $this->post("coupon",
            [ 'changer_id' => $changerId,
                'code' => $code,
                'description' => $description,
                'quantity' => $quantity,
                'duration_minutes' => $durationMinutes,
                'expire_at' => $expireAt,
                'settlement_price' => $settlementPrice
            ]);
    }

    public function updateCoupon($changerId,
                                 $couponId,
                                 $description,
                                 $quantity,
                                 $durationMinutes,
                                 $expireAt,
                                 $settlementPrice)
    {
        return $this->put("coupon/{$couponId}",
            [ 'changer_id' => $changerId,
                'description' => $description,
                'quantity' => $quantity,
                'duration_minutes' => $durationMinutes,
                'expire_at' => $expireAt,
                'settlement_price' => $settlementPrice
            ]);
    }

    public function deleteCoupon($changerId, $couponId)
    {
        return $this->post("coupon/{$couponId}/delete", [ 'changer_id' => $changerId ]);
    }

    public function getCoupons($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("coupon/", $filterArray);
    }

    public function getCoupon($couponId, $filterArray = [])
    {
        return $this->get("coupon/{$couponId}", $filterArray);
    }

    public function getCouponByCode($code, $filterArray = [])
    {
        return $this->get("coupon/code/{$code}", $filterArray);
    }

    public function getTotalCouponUseByCode($code, $filterArray = [])
    {
        return $this->get("coupon_use_history/code/{$code}/total", $filterArray);
    }

    public function registerCouponByCode($changerId, $code, $userId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId
        ];

        return $this->post("/coupon_use_history/code/{$code}", $inputs);
    }

    public function expireCouponUseHistories($changerId)
    {
        return $this->post("/coupon_use_history/expire_items", ['changer_id' => $changerId]);
    }

    public function getCouponUseHistory($couponUseHistoryId)
    {
        return $this->get("/coupon_use_history/{$couponUseHistoryId}");
    }

    public function getCouponUseHistoriesBySettlement($settlementYear, $settlementMonth, $filterArray = [])
    {
        return $this->get("/coupon_use_history/settlement_year/{$settlementYear}/settlement_month/{$settlementMonth}", $filterArray);
    }

    public function createEvent(
        $changerId,
        $title,
        $description,
        $meta,
        $condition,
        $price,
        $quantity,
        $isShow,
        $isActive,
        $group_id
    )
    {
        $inputs = [
            'changer_id' => $changerId,
            'title' => $title,
            'description' => $description,
            'meta' => $meta,
            'order_condition' => $condition,
            'price' => $price,
            'quantity' => $quantity,
            'is_show' => $isShow,
            'is_active' => $isActive,
            'group_id' => $group_id
        ];

        return $this->put("event", $inputs);
    }

    public function createEvent2(
        $changerId,
        $title,
        $description,
        $meta,
        $condition,
        $price,
        $quantity,
        $isShow,
        $isActive,
        $imageUrl
    )
    {
        $inputs = [
            'changer_id' => $changerId,
            'title' => $title,
            'description' => $description,
            'meta' => $meta,
            'order_condition' => $condition,
            'price' => $price,
            'quantity' => $quantity,
            'is_show' => $isShow,
            'is_active' => $isActive,
            'image_url' => $imageUrl
        ];

        return $this->put("event", $inputs);
    }

    public function getEvents($page = 1,
                              $limit = 10,
                              $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("event", $filterArray);
    }

    public function getEventsByIds($eventIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $eventIds);
        return $this->get("event/event_ids", $filterArray);
    }

    // deprecated
    public function getEventsBySetId($setId, $filterArray = [])
    {
        return $this->get("event/set/{$setId}", $filterArray);
    }

    // deprecated
    public function getEventsBySetIds($setIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $setIds);
        return $this->get("event/set_ids", $filterArray);
    }

    public function getEventsByGroupId($groupId, $filterArray = [])
    {
        return $this->get("event/group/{$groupId}", $filterArray);
    }

    public function getEventsByGroupIds($groupIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $groupIds);
        return $this->get("event/group_ids", $filterArray);
    }

    public function getEvent($eventId)
    {
        return $this->get("event/{$eventId}");
    }

    public function updateEvent(
        $changerId,
        $eventId,
        $title,
        $description,
        $meta,
        $condition,
        $price,
        $quantity,
        $isShow,
        $isActive
    )
    {
        $inputs = [
            'changer_id' => $changerId,
            'title' => $title,
            'description' => $description,
            'meta' => $meta,
            'order_condition' => $condition,
            'price' => $price,
            'quantity' => $quantity,
            'is_show' => $isShow,
            'is_active' => $isActive
        ];

        return $this->post("event/{$eventId}", $inputs);
    }

    public function updateEvent2(
        $changerId,
        $eventId,
        $title,
        $description,
        $meta,
        $condition,
        $price,
        $quantity,
        $isShow,
        $isActive,
        $imageUrl
    )
    {
        $inputs = [
            'changer_id' => $changerId,
            'title' => $title,
            'description' => $description,
            'meta' => $meta,
            'order_condition' => $condition,
            'price' => $price,
            'quantity' => $quantity,
            'is_show' => $isShow,
            'is_active' => $isActive,
            'image_url' => $imageUrl,
        ];

        return $this->post("event/{$eventId}", $inputs);
    }

    public function deleteEvent($changerId, $eventId)
    {
        return $this->post("event/{$eventId}/delete",
            [ 'changer_id' => $changerId ]
        );
    }

    public function createPlanToken($changerId, $planId, $expireDate, $isReuseable, $quantity)
    {
        $inputs = [
            'changer_id' => $changerId,
            'plan_id' => $planId,
            'expire_date' => $expireDate,
            'is_reuseable' => $isReuseable,
            'quantity' => $quantity
        ];

        return $this->post("plan_token", $inputs);
    }

    public function updatePlanToken($changerId, $planTokenId, $expireDate, $quantity)
    {
        $inputs = [
            'changer_id' => $changerId,
            'expire_date' => $expireDate,
            'quantity' => $quantity
        ];

        return $this->put("plan_token/{$planTokenId}", $inputs);
    }

    public function getPlanTokenByToken($token, $filterArray)
    {
        return $this->get("plan_token/token/{$token}", $filterArray);
    }

    public function getPlanTokenByUser($userId)
    {
        return $this->get("plan_token/user/{$userId}");
    }

    public function expirePlanToken($changerId, $userId, $token, $expiredAt)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'token' => $token,
            'expired_at' => $expiredAt
        ];

        return $this->post("plan_token/token", $inputs);
    }

    public function getPlanTokens($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("plan_token", $filterArray);
    }

    public function getPlanToken($planTokenId)
    {
        return $this->get("plan_token/{$planTokenId}");
    }

    public function getPointHistories($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("point_history", $filterArray);
    }

    public function getPointHistoriesByUserId($userId, $page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("point_history/user/{$userId}", $filterArray);
    }

    public function getPointHistoriesSumByUserId($userId)
    {
        return $this->get("point_history/user/{$userId}/sum");
    }

    public function getPointHistory($pointHistoryId)
    {
        return $this->get("point_history/{$pointHistoryId}");
    }

    public function createPointHistoryByAdmin(
        $userId,
        $delta,
        $adminId,
        $note
    )
    {
        $input = [
            'user_id' => $userId,
            'delta' => $delta,
            'transaction_type' => static::POINT_HISTORY_TRANSACTION_TYPE_ADJUSTED_BY_ADMIN,
            'admin_id' => $adminId,
            'note' => $note
        ];

        return $this->post("point_history", $input);
    }

    public function createPointHistoryByReferee(
        $refereeId,
        $referrerId,
        $delta,
        $note
    )
    {
        $input = [
            'user_id' => $referrerId,
            'delta' => $delta,
            'transaction_type' => static::POINT_HISTORY_TRANSACTION_TYPE_REWORDED_BY_REFERER,
            'referee_id' => $refereeId,
            'note' => $note
        ];

        return $this->post("point_history", $input);
    }

    public function createPointHistoryByPayment(
        $userId,
        $delta,
        $note,
        $paymentId = null
    )
    {
        $input = [
            'user_id' => $userId,
            'delta' => $delta,
            'transaction_type' => static::POINT_HISTORY_TRANSACTION_TYPE_USED_FOR_PAYMENT,
            'payment_id' => $paymentId,
            'note' => $note
        ];

        return $this->post("point_history", $input);
    }

    public function createPointHistoryByDailyViewContentReward(
        $userId,
        $delta,
        $note
    )
    {
        $input = [
            'user_id' => $userId,
            'delta' => $delta,
            'transaction_type' => static::POINT_HISTORY_TRANSACTION_TYPE_REWARDED_BY_DAILY_VIEW_CONTENT,
            'note' => $note
        ];

        return $this->post("point_history", $input);
    }

    public function createPointHistoryByAppInstall($userId, $delta, $note = '')
    {
        $input = [
            'user_id' => $userId,
            'delta' => $delta,
            'transaction_type' => static::POINT_HISTORY_TRANSACTION_TYPE_APP_INSTALL,
            'note' => $note
        ];

        return $this->post('point_history', $input);
    }

    public function updatePointHistory($pointHistoryId, $note)
    {
        $input = [
            'note' => $note
        ];

        return $this->put("point_history/{$pointHistoryId}", $input);
    }

    public function createPointHistoriesByAdmin(
        $userIds,
        $delta,
        $adminId,
        $note
    )
    {
        $input = [
            'user_ids' => implode(',', $userIds),
            'delta' => $delta,
            'transaction_type' => static::POINT_HISTORY_TRANSACTION_TYPE_ADJUSTED_BY_ADMIN,
            'admin_id' => $adminId,
            'note' => $note
        ];

        return $this->post("point_history/store_point_histories_by_admin", $input);
    }

    public function createPointHistoryByReferralSignup(
        $userId,
        $referrerId,
        $delta,
        $transactionType,
        $note = null
    ) {
        $input = [
            'user_id' => $userId,
            'referrer_id' => $referrerId,
            'delta' => $delta,
            'transaction_type' => $transactionType,
            'note' => $note
        ];

        return $this->post("point_history", $input);
    }

    public function updateOrCreateReferralRelation($referrerId, $refereeId, $referralProgramId)
    {
        $inputs = [
            'referrer_id' => $referrerId,
            'referee_id' => $refereeId,
            'referral_program_id' => $referralProgramId
        ];

        return $this->put("referral_relation", $inputs);
    }

    public function getReferralRelationByUser($userId) // getReferralRelationByReferee 로 대체됨 사용되는 곳이 없을시 지웁니다.
    {
        return $this->get("referral_relation/referee/{$userId}");
    }

    public function getReferralRelationByReferee($userId)
    {
        return $this->get("referral_relation/referee/{$userId}");
    }

    public function getPointSumByUser($userId)
    {
        return $this->get("point_history/user/{$userId}/sum");
    }

    public function getPointReceivedSumByUser($userId)
    {
        return $this->get("point_history/user/{$userId}/sum-received");
    }

    public function getPointSumByPayment($paymentId)
    {
        return $this->get("point_history/payment/{$paymentId}/sum");
    }

    public function getPointSumByOrders($orderIds)
    {
        $orderIds = implode(',', $orderIds);
        return $this->get("point_history/by_payments/sum", [
            'order_ids' => $orderIds
        ]);
    }

    public function getReferralProgram($referralProgramId)
    {
        return $this->get("referral_program/{$referralProgramId}");
    }

    public function getPayment($paymentId)
    {
        return $this->get("payment/{$paymentId}");
    }

    public function getPayments($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("payment", $filterArray);
    }

    public function getPaymentsNoPagination($filterArray = [])
    {
        $filterArray['limit'] = static::NO_PAGE_LIMIT;
        return $this->get("payment", $filterArray);
    }

    public function getPaymentByOrder($orderId)
    {
        return $this->get("payment/order/{$orderId}");
    }

    //    user default plan
    public function getUserDefaultPlansByUser($userId, $page =1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("user_default_plan/user/{$userId}", $filterArray);
    }

    public function getUserDefaultPlan($userDefaultPlanId)
    {
        return $this->get("user_default_plan/{$userDefaultPlanId}");
    }

    public function createUserDefaultPlan($changerId, $inputs)
    {
        $inputs['changer_id'] = $changerId;

        return $this->post("user_default_plan", $inputs);
    }

    public function updateUserDefaultPlanByUserDefaultPlan($changerId, $userDefaultPlanId, $inputs)
    {
        $inputs['changer_id'] = $changerId;
        return $this->put("user_default_plan/{$userDefaultPlanId}", $inputs);
    }

    public function getPlanByToken($token, $filterArray = [])
    {
        return $this->get("plan/token/{$token}", $filterArray);
    }

    public function getCheapestPlanByUser($userId, $filterArray = [])
    {
        return $this->get("plan/user/{$userId}/cheapest", $filterArray);
    }

    public function addBankTransferAndOrderAndPayVoucher(
        $changerId,
        $userId,
        $name,
        $price,
        $planId,
        $quantity)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultBankTransfer = $this->addBankTransfer(
            $changerId,
            $userId,
            $name);

        if (!$resultBankTransfer['success']) {
            $result['success'] = false;
            $result['from'] = 'bank_transfer';
            $result['error_code'] = $resultBankTransfer['error_code'];
            $result['message'] = $resultBankTransfer['message'];
            return $result;
        }

        $bankTransfer = $resultBankTransfer['item'];
        $result['bank_transfer'] = $bankTransfer;
        // 정상적으로 카드 등록 되었음.

        try {
            $inputs = [ 'changer_id' => $changerId,
                'plan_id' => $planId,
                'user_id' => $userId,
                'quantity' => $quantity,
                'price' => $price
            ];

            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
            return $result;
        }

        // order
        $order = $resultOrder['success']['data'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->pay2(
            $changerId,
            $userId,
            $order['id'],
            static::PAYMENT_TYPE_BANK_TRANSFER,
            'bank_transfer_id',
            $bankTransfer['id'],
            false,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        $result['bank_transfer'] = $bankTransfer;
        return $result;
    }

    public function addCreditCardAndOrderAndPayVoucher(
        $changerId,
        $userId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password,
        $price,
        $planId,
        $quantity)
    {
        $result = [ 'success' => false ];

        // add credit card
        $resultCreditCard = $this->addCreditCard(
            $userId,
            $creditCardNumber,
            $expireYear,
            $expireMonth,
            $id,
            $password);

        if (!$resultCreditCard['success']) {
            $result['success'] = false;
            $result['from'] = 'credit_card';
            $result['error_code'] = $resultCreditCard['error_code'];
            $result['message'] = $resultCreditCard['message'];
            return $result;
        }

        $creditCard = $resultCreditCard['item'];
        // 정상적으로 카드 등록 되었음.

        $result = $this->orderAndPayVoucher(
            $changerId,
            $userId,
            $price,
            $planId,
            $quantity,
            static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD,
            'credit_card_id',
            $creditCard['id']
        );

        return $result;
    }

    public function orderAndPayVoucher(
        $changerId,
        $userId,
        $price,
        $planId,
        $quantity,
        $pgType,
        $paymentMethodIdName,
        $paymentMethodId)
    {
        $result = [ 'success' => false ];

        try {
            $inputs = [ 'changer_id' => $changerId,
                'plan_id' => $planId,
                'user_id' => $userId,
                'quantity' => $quantity,
                'price' => $price
            ];

            $resultOrder = $this->post('order', $inputs);
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['from'] = 'order';
            $result['error_code'] = $e->getCode();
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
            return $result;
        }

        // order
        $order = $resultOrder['success']['data'];
        // 정상적으로 주문 되었음.

        // payment
        $resultPayment = $this->pay2(
            $changerId,
            $userId,
            $order['id'],
            $pgType,
            $paymentMethodIdName,
            $paymentMethodId,
            true,
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];
            return $result;
        }

        $payment = $resultPayment['item'];

        $orderResult = $this->get("order/".$order['id'], []);
        $order = $orderResult['success']['data'];

        $result['success'] = true;
        $result['order'] = $order;
        $result['payment'] = $payment;
        return $result;
    }

    public function createVoucher($changerId, $userId, $voucherOptionId, $planId, $sentTo)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'voucher_option_id' => $voucherOptionId,
            'plan_id' => $planId,
            'sent_to' => $sentTo
        ];

        return $this->post("voucher", $inputs);
    }

    public function createVouchersByLengthMonth($changerId, $userId, $lengthMonth, $emails)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'length_month' => $lengthMonth,
            'emails' => implode(',', $emails)
        ];

        return $this->post("voucher/store_vouchers_by_length_month", $inputs);
    }

    public function getVoucherByCode($code)
    {
        return $this->get("voucher/code/{$code}");
    }

    public function cancelVoucher($changerId, $voucherId)
    {
        return $this->put("voucher/{$voucherId}", [
            'changer_id' => $changerId,
            'action' => 'cancel'
        ]);
    }

    public function resendVoucher($changerId, $voucherId)
    {
        return $this->put("voucher/resend_voucher/{$voucherId}", [
            'changer_id' => $changerId
        ]);
    }

    public function getVoucherDiscountRates($filterArray = [])
    {
        return $this->get("voucher_discount_rate", $filterArray);
    }

    public function getVoucherOption($voucherOptionId)
    {
        return $this->get("voucher_option/{$voucherOptionId}");
    }

    public function getVoucherOptions($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("voucher_option", $filterArray);
    }

    public function getVoucherOptionsByUser($userId, $filterArray = [])
    {
        return $this->get("voucher_option/user/{$userId}", $filterArray);
    }

    public function getVoucherByVoucherOptions($voucherOptionIds, $filterArray = [])
    {
        $filterArray['voucher_option_ids'] = implode(',', $voucherOptionIds);
        return $this->get("voucher/voucher_options", $filterArray);
    }

    public function registerVoucherByCode($changerId, $userId, $code, $force = false)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'force' => $force ? 1 : 0
        ];

        return $this->post("voucher_use_history/code/{$code}", $inputs);
    }

    public function registerVoucherUseHistoryByPartnerUser($changerId, $userId, $planId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'plan_id' => $planId
        ];

        return $this->post("voucher_use_history/user/{$userId}", $inputs);
    }

    public function getVoucherUseHistoriesBySettlement($settlementYear, $settlementMonth, $filterArray = [])
    {
        return $this->get("voucher_use_history/settlement_year/{$settlementYear}/settlement_month/{$settlementMonth}", $filterArray);
    }

    public function getVoucherUseHistoriesByUser($userId, $filterArray = [])
    {
        return $this->get("voucher_use_history/user/{$userId}", $filterArray);
    }

    public function expireVoucherUseHistories($changerId)
    {
        return $this->post(
            "voucher_use_history/expire_voucher_use_histories",
            [
                'changer_id' => $changerId
            ]);
    }

    public function refreshVoucherUseHistories($changerId, $days)
    {
        return $this->post("voucher_use_history/refresh_voucher_use_histories", [
            'changer_id' => $changerId,
            'base_days' => $days
        ]);
    }

    public function createVoucherOption($changerId, $userId, $planId, $quantity, $note)
    {
        return $this->post("voucher_option", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'plan_id' => $planId,
            'quantity' => $quantity,
            'note' => $note
        ]);
    }

    public function createVoucherOptions($changerId, $userIds, $planId, $quantity, $note)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_ids' => implode(',', $userIds),
            'plan_id' => $planId,
            'quantity' => $quantity,
            'note' => $note
        ];

        return $this->post("voucher_option/store_voucher_options", $inputs);
    }

    public function getVouchers($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("voucher", $filterArray);
    }

    public function getVoucherUseHistoryByVoucher($voucherId)
    {
        return $this->get("voucher_use_history/voucher/{$voucherId}");
    }

    public function getVoucherUseHistoriesByVoucherIds($voucherIds, $filterArray = [])
    {
        $filterArray['voucher_ids'] = implode(',', $voucherIds);
        return $this->get("voucher_use_history/voucher_ids", $filterArray);
    }

    public function getVoucher($voucherId)
    {
        return $this->get("voucher/{$voucherId}");
    }

    public function expireVoucherUseHistory($changerId, $voucherUseHistoryId)
    {
        return $this->put("voucher_use_history/{$voucherUseHistoryId}", [
            'changer_id' => $changerId,
            'action' => 'expire'
        ]);
    }

    public function updateVoucherOptionQuantity($changerId, $voucherOptionId, $quantity, $note)
    {
        return $this->put("voucher_option/{$voucherOptionId}", [
            'changer_id' => $changerId,
            'quantity' => $quantity,
            'note' => $note
        ]);
    }

    public function existsCreditCardByUserId($userId)
    {
        return $this->get("credit_card/user/{$userId}/exists");
    }

    public function existsNaverpayByUserId($userId)
    {
        return $this->get("naverpay/user/{$userId}/exists");
    }

    public function existsPaymentMethodByPgType($userId, $pgType)
    {
        if ($pgType == static::PAYMENT_TYPE_NICEPAY_CREDIT_CARD) {
            return $this->existsCreditCardByUserId($userId);
        } else if ($pgType == static::PAYMENT_TYPE_NAVERPAY) {
            return $this->existsNaverpayByUserId($userId);
        } else {
            return $result['success']['data'] = false;
        }
    }

    public function getNaverpayDifferenceWithHistory($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get('naverpay_difference/payment/history', $filterArray);
    }


    public function createNaverpayDifference(
        $changerId,
        $paymentId,
        $payHistId,
        $cancel,
        $tradeDate,
        $amount,
        $productName,
        $usedKey = PublyPaymentService::NAVERPAY_USED_KEY_MEMBERSHIP
    ) {
        $inputs = [
            'changer_id' => $changerId,
            'payment_id' => $paymentId,
            'pay_hist_id' => $payHistId,
            'cancel' => $cancel,
            'trade_date' => $tradeDate,
            'amount' => $amount,
            'product_name' => $productName,
            $usedKey
        ];

        return $this->post('naverpay_difference', $inputs);
    }


    public function createNaverpayDifferenceRequests($changerId, $batch = 'request')
    {
        return $this->post("naverpay_difference/requests", [
            'changer_id' => $changerId,
            'batch' => $batch,
        ]);
    }

    public function createNaverpayDifferenceResults($changerId, $batch = 'result')
    {
        return $this->post("naverpay_difference/results", [
            'changer_id' => $changerId,
            'batch' => $batch,
        ]);
    }

    public function createNaverpayDifferenceRequestBatch($changerId)
    {
        return $this->post("naverpay_difference/request_batch", [
            'changer_id' => $changerId,
        ]);
    }

    public function createNaverpayDifferenceResultBatch($changerId, $completeDate = null)
    {
        $inputs = [
            'changer_id' => $changerId
        ];
        if ($completeDate) {
            $inputs = array_merge($inputs, ['complete_date' => $completeDate]);
        }

        return $this->post("naverpay_difference/result_batch", $inputs);
    }

    public function getCreditCardFreeInterestInstallments()
    {
        $cacheKey = 'FREE_INTEREST_INSTALLMENT';
        return \Cache::remember($cacheKey, 1440, function () {
            return $this->get("credit_card/free_interests_installments");
        });
    }

    public function createPlan($changerId, $name, $price, $settlementPrice, $note, $lengthMonth, $nextPlanId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'name' => $name,
            'price' => $price,
            'settlement_price' => $settlementPrice,
            'note' => $note,
            'length_month' => $lengthMonth,
            'next_plan_id' => $nextPlanId
        ];

        return $this->post("plan", $inputs);
    }

    public function updatePlan($changerId, $planId, $name, $price, $settlementPrice, $note, $lengthMonth, $nextPlanId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'name' => $name,
            'price' => $price,
            'settlement_price' => $settlementPrice,
            'note' => $note,
            'length_month' => $lengthMonth,
            'next_plan_id' => $nextPlanId
        ];

        return $this->put("plan/{$planId}", $inputs);
    }

    public function updatePlanNextPlanId($changerId, $planId, $nextPlanId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'next_plan_id' => $nextPlanId
        ];

        return $this->put("plan/{$planId}", $inputs);
    }

    public function getCouponOption($couponOptionId)
    {
        return $this->get("coupon_option/{$couponOptionId}");
    }

    public function getCouponOptionByCode($code)
    {
        return $this->get("coupon_option/code/{$code}");
    }


    public function getCouponOptions($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;

        return $this->get("coupon_option", $filterArray);
    }

    public function getCouponOptionsByProject($projectId, $filterArray = [])
    {
        return $this->get("coupon_option/project/{$projectId}", $filterArray);
    }

    public function createCouponOption($name, $projectId, $discountPrice, $expireAt, $note)
    {
        $inputs = [
            'name' => $name,
            'project_id' => $projectId,
            'discount_price' => $discountPrice,
            'expire_at' => $expireAt,
            'note' => $note
        ];

        return $this->post("coupon_option", $inputs);
    }

    public function updateCouponOption($couponOptionId, $name, $discountPrice, $expireAt, $note)
    {
        $inputs = [
            'name' => $name,
            'discount_price' => $discountPrice,
            'expire_at' => $expireAt,
            'note' => $note
        ];

        return $this->put("coupon_option/{$couponOptionId}", $inputs);
    }

    public function getCouponsV2ByUserAndCouponOptionIds($userId, $couponOptionIds, $filterArray = [])
    {
        $filterArray['coupon_option_ids'] = implode(',', $couponOptionIds);

        return $this->get("coupon_v2/user/{$userId}/by_coupon_option_ids", $filterArray);
    }

    public function createCouponV2($changerId, $userId, $couponOptionId)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'coupon_option_id' => $couponOptionId
        ];

        return $this->post("coupon_v2", $inputs);
    }

    public function updateCouponOptionIsActive($couponOptionId, $isActive)
    {
        $inputs = [
            'is_active' => $isActive
        ];

        return $this->put("coupon_option/{$couponOptionId}", $inputs);
    }

    public function getCouponV2($couponV2Id)
    {
        return $this->get("coupon_v2/{$couponV2Id}");
    }

    public function updateCouponV2($changerId, $couponV2Id, $orderId, $status)
    {
        $inputs = [
            'changer_id' => $changerId,
            'status' => $status,
            'order_id' => $orderId
        ];

        return $this->put("coupon_v2/{$couponV2Id}", $inputs);
    }

    public function getCouponV2sByOrder($orderId, $filterArray = [])
    {
        return $this->get("coupon_v2/order/{$orderId}", $filterArray);
    }

    public function refundSubscriptionRenewalHistory(
        $subscriptionRenewalHistoryId,
        $payCancelAmount,
        $pointCancelAmount,
        $cancelReason,
        $requestUserId,
        $force = false)
    {
        $inputs = [
            'pay_cancel_amount' => $payCancelAmount,
            'point_cancel_amount' => $pointCancelAmount,
            'cancel_reason' => $cancelReason,
            'request_user_id' => $requestUserId,
            'force' => $force,
        ];
        return $this->post("/refund/subscription_renewal_history/{$subscriptionRenewalHistoryId}", $inputs);
    }

    public function refundOrder(
        $orderId,
        $payCancelAmount,
        $pointCancelAmount,
        $cancelReason,
        $requestUserId,
        $force = false)
    {
        $inputs = [
            'pay_cancel_amount' => $payCancelAmount,
            'point_cancel_amount' => $pointCancelAmount,
            'cancel_reason' => $cancelReason,
            'request_user_id' => $requestUserId,
            'force' => $force,
        ];
        return $this->post("/refund/order/{$orderId}", $inputs);
    }

    public function getRefundHistory($page, $limit, $filterArray = [])
    {
        return $this->get("refund_history", $filterArray);
    }

    public function getOrderCountsByRewardIds($rewardIds, $filterArray = [])
    {
        $filterArray['reward_ids'] = implode(',', $rewardIds);
        return $this->get("/order/by_reward_ids/count", $filterArray);
    }

    public function notifyOrderByDaysAfterPaid($daysAfterPaid)
    {
        return $this->post("/order/notify_order_by_days_after_paid", [
            'days_after_paid' => $daysAfterPaid
        ]);
    }

    public function notifyCouponExpired()
    {
        return $this->post("/coupon_v2/notify_coupon_expired");
    }

    public function notifyUpcomingFreeTrialExpired($daysBeforeExpired)
    {
        return $this->post("/subscription/notify_free_trial_expired", [
            'days_before_expired' => $daysBeforeExpired
        ]);
    }

    public function notifyMembershipOnboardingGuide($daysAfterRegister)
    {
        return $this->post("/subscription/notify_membership_onboarding_guide", [
            'days_after_register' => $daysAfterRegister
        ]);
    }
}
