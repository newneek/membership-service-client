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
    const ORDER_STATUS_MAX = 10;
    
    const STRING_ORDER_STATUS = [
        PublyPaymentService::ORDER_STATUS_CHECKEDOUT => "주문완료",
        PublyPaymentService::ORDER_STATUS_WAITING_PAYMENT => "결제대기",
        PublyPaymentService::ORDER_STATUS_PAID => "결제성공",
        PublyPaymentService::ORDER_STATUS_CANCELLED => "결제전 취소",
        PublyPaymentService::ORDER_STATUS_PROJECT_FAILED => "프로젝트 실패",
        PublyPaymentService::ORDER_STATUS_PAYMENT_IN_PROGRESS => "결제중",
        PublyPaymentService::ORDER_STATUS_PAYMENT_FAILED => "결제실패",
        PublyPaymentService::ORDER_STATUS_REFUND_REQUESTED => "환불 신청",
        PublyPaymentService::ORDER_STATUS_REFUND_COMPLETED => "환불 완료" ];

    const STRING_PAYMENT_TYPE = [
        PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD => "Nicapay 신용카드",
        PublyPaymentService::PAYMENT_TYPE_ADMIN => "관리자 추가",
        PublyPaymentService::PAYMENT_TYPE_BANK_TRANSFER => "계좌이체",
        PublyPaymentService::PAYMENT_TYPE_PAYPAL => "PayPal",
        PublyPaymentService::PAYMENT_TYPE_IAMPORT => "아임포트",
        PublyPaymentService::PAYMENT_TYPE_OLD_ADMIN => "구 관리자 추가"
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
                                $force = false)
    {
        $result = [ 'success' => false ];
        try {
            $inputs = [ 'changer_id' => $changerId,
                        'force' => $force ? 1 : 0,
                        'action' => 'modify',
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
}
