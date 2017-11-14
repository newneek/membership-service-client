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
    const PAYMENT_TYPE_IAMPORT = 90;
    const PAYMENT_TYPE_OLD_ADMIN = 91;

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
    const ORDER_STATUS_MAX = 11;

    const PAYMENT_STATUS_WAITING = 1;
    const PAYMENT_STATUS_COMPLETED = 2;
    const PAYMENT_STATUS_FAILED = 3;
    const PAYMENT_STATUS_IN_PROGRESS = 4;

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

    const STRING_ORDER_STATUS = [
        PublyPaymentService::ORDER_STATUS_CHECKEDOUT => "주문완료",
        PublyPaymentService::ORDER_STATUS_WAITING_PAYMENT => "결제대기",
        PublyPaymentService::ORDER_STATUS_PAID => "결제성공",
        PublyPaymentService::ORDER_STATUS_CANCELLED => "결제전 취소",
        PublyPaymentService::ORDER_STATUS_PROJECT_FAILED => "프로젝트 실패",
        PublyPaymentService::ORDER_STATUS_PAYMENT_IN_PROGRESS => "결제중",
        PublyPaymentService::ORDER_STATUS_PAYMENT_FAILED => "결제실패",
        PublyPaymentService::ORDER_STATUS_REFUND_REQUESTED => "환불 신청",
        PublyPaymentService::ORDER_STATUS_REFUND_COMPLETED => "환불 완료",
        PublyPaymentService::ORDER_STATUS_PROJECT_DROP => "프로젝트 중단"
    ];

    const STRING_PAYMENT_STATUS = [
        PublyPaymentService::PAYMENT_STATUS_WAITING => "결제대기",
        PublyPaymentService::PAYMENT_STATUS_COMPLETED => "결제성공",
        PublyPaymentService::PAYMENT_STATUS_FAILED => "결제실패",
        PublyPaymentService::PAYMENT_STATUS_IN_PROGRESS => "결제중"
    ];

    const STRING_PAYMENT_TYPE = [
        PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD => "Nicepay 신용카드",
        PublyPaymentService::PAYMENT_TYPE_ADMIN => "관리자 추가",
        PublyPaymentService::PAYMENT_TYPE_BANK_TRANSFER => "계좌이체",
        PublyPaymentService::PAYMENT_TYPE_PAYPAL => "PayPal",
        PublyPaymentService::PAYMENT_TYPE_IAMPORT => "아임포트",
        PublyPaymentService::PAYMENT_TYPE_OLD_ADMIN => "구 관리자 추가"
    ];

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

    public function orderAndPay(
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
                    'immediate' => false
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
                    'immediate' => false
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
                    'note' => $note
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
            $resultApi =
                $this->put("/payment/{$paymentId}", $inputs);
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

    public function subscriptionAndPay($changerId, $userId, $creditCardId, $planId, $price)
    {
        $result = [ 'success' => false ];

        // subscription
        $resultSubscription= $this->subscription(
            $changerId,
            $userId,
            $planId,
            $price
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
            '');

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $payment = $resultPayment['item'];

        $subscriptionResult = $this->get("subscription/{$subscription['id']}", []);
        $subscription = $subscriptionResult['success']['data'];

        $result['success'] = true;
        $result['subscription'] = $subscription;
        $result['payment'] = $payment;
        return $result;
    }

    public function addCreditCardAndSubscriptionAndPay($changerId,
                                                       $userId,
                                                       $creditCardNumber,
                                                       $expireYear,
                                                       $expireMonth,
                                                       $id,
                                                       $password,
                                                       $planId,
                                                       $price)
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
        $creditCardId = $creditCard['id'];
        $result['creditCard'] = $creditCard;

        // subscription
        $resultSubscription= $this->subscription(
            $changerId,
            $userId,
            $planId,
            $price
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
            '');

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

    public function paySubscription(
        $changerId,
        $userId,
        $subscriptionId,
        $pgType,
        $paymentMethodIdName,
        $paymentMethodId,
        $note
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
                    'note' => $note
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

    public function changeCardAndKeepSubscription(
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

        $resultPayment = $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'renewal',
                'force' => $force ? 1 : 0 ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function addCreditCardAndKeepSubscription(
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

        $resultPayment = $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'renewal',
                'force' => $force ? 1 : 0 ]);

        if (!$resultPayment['success']) {
            $result['success'] = false;
            $result['from'] = 'payment';
            $result['error_code'] = $resultPayment['error_code'];
            $result['message'] = $resultPayment['message'];

            return $result;
        }

        $subscriptionResult = $this->get("subscription/{$subscriptionId}", []);
        $subscription = $subscriptionResult['success']['data'];
        $result['subscription'] = $subscription; // refresh subscription after payment

        $result['success'] = true;
        return $result;
    }

    public function keepSubscription($changerId, $subscriptionId, $force = false)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'renewal',
                'force' => $force ? 1 : 0 ]);
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

    public function addCreditCardAndRecoverSubscription($changerId,
                                                        $userId,
                                                        $subscriptionId,
                                                        $paymentId,
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
        $result['creditCard'] = $creditCard;
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
                'action' => 'expired',
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

    public function expireSubscriptions($changerId, $days)
    {
        return $this->put("/subscription/expire_subscriptions",
            ['changer_id' => $changerId,
                'renew_day' => implode(',', $days)]);
    }

    public function updateSubscription($changerId, $subscriptionId, $note, $force = false)
    {
        return $this->put("/subscription/{$subscriptionId}",
            [ 'changer_id' => $changerId,
                'action' => 'update',
                'note' => $note,
                'force' => $force ? 1 : 0 ]);
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

    public function getSubscriptionRenewalHistoriesBySettlement($settlementYear, $settlementMonth, $filterArray = [])
    {
        return $this->get("subscription_renewal_history/settlement_year/{$settlementYear}/settlement_month/{$settlementMonth}", $filterArray);
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

    public function getPlans()
    {
        return $this->get("plan");
    }

    public function getPlan($planId)
    {
        return $this->get("plan/{$planId}");
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

    public function addCreditCardAndChangeSubscriptionCreditCard(
        $changerId,
        $userId,
        $paymentId,
        $creditCardNumber,
        $expireYear,
        $expireMonth,
        $id,
        $password)
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

        $result['success'] = true;
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

    public function deleteCoupon($changerId, $couponId)
    {
        return $this->post("coupon/delete", [ 'changer_id' => $changerId,
            'coupon_id' => $couponId ]);
    }

    public function getCoupons($page, $limit, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("coupon/", $filterArray);
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

    public function getCouponUseHistoriesBySettlement($settlementYear, $settlementMonth, $filterArray = [])
    {
        return $this->get("/coupon_use_history/settlement_year/{$settlementYear}/settlement_month/{$settlementMonth}", $filterArray);
    }

}
