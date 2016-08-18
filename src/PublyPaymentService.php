<?php

namespace App\Services;

use App\Services\Api\BaseApiService;
use App\Services\Api\ResponseException;

class PublyPaymentService extends BaseApiService {

	const PAYMENT_TYPE_NICEPAY_CREDIT_CARD = 1;
    const PAYMENT_TYPE_ADMIN = 2;

    const ORDER_STATUS_CHECKEDOUT = 1; // no payment was assigned.
    const ORDER_STATUS_WAITING_PAYMENT = 2; // Reserved Payment. payment can be canceled, changed.
    const ORDER_STATUS_PAID = 3;
    const ORDER_STATUS_CANCELLED = 4;
    const ORDER_STATUS_PROJECT_FAILED = 5;
    const ORDER_STATUS_PAYMENT_IN_PROGRESS = 6; // Right before to make payment from reserved status(user can't change or cancel payment) 
    const ORDER_STATUS_PAYMENT_FAILED = 7;
    
    const STRING_ORDER_STATUS = [
        PublyPaymentService::ORDER_STATUS_CHECKEDOUT => "주문완료",
        PublyPaymentService::ORDER_STATUS_WAITING_PAYMENT => "결제대기",
        PublyPaymentService::ORDER_STATUS_PAID => "결제성공",
        PublyPaymentService::ORDER_STATUS_CANCELLED => "결제전 취소",
        PublyPaymentService::ORDER_STATUS_PROJECT_FAILED => "프로젝트 실패",
        PublyPaymentService::ORDER_STATUS_PAYMENT_IN_PROGRESS => "결제중",
        PublyPaymentService::ORDER_STATUS_PAYMENT_FAILED => "결제실패"];

    const STRING_PAYMENT_TYPE = [
        PublyPaymentService::PAYMENT_TYPE_NICEPAY_CREDIT_CARD => "신용카드",
        PublyPaymentService::PAYMENT_TYPE_ADMIN => "관리자 추가"];

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";        
    }

    /*
     * Order related functions
     */
    public function getOrdersByUser($userId, $page, $limit)
    {
        return $this->get("order/user/{$userId}", 
                          [ 'page' => $page,
                            'limit' => $limit ]);
    }

    public function getTotalOrdersByRewardId($rewardId)
    {
    	return $this->get("order/reward/{$rewardId}/total");
    }

    public function getOrdersByProjectId($projectId, $page = 1, $limit = 10)
    {
        return $this->get("order/project/{$projectId}", [ 'page' => $page, 
                                                          'limit' => $limit ]);
    }

    public function order(
                        $userId,
                        $contentId,
                        $rewardId,
                        $price,
                        $userName,
                        $userEmail,
                        $userPhone)
    {
        $result = [ 'success' => false ];
        try {
            $resultOrder = 
                $this->post('order', [  
                    'changer_id' => $userId,
                    'content_id' => $contentId,
                    'reward_id' => $rewardId,
                    'user_id' => $userId,
                    'price' => $price,
                    'user_name' => $userName,
                    'user_email' => $userEmail,
                    'user_phone' => $userPhone
                ]);
        } catch (ResponseException $e) {
            $result['success'] = false;
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
                        $userPhone)
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
		    					$userPhone);

    	if (!$resultOrder['success']) {
    		$result['success'] = false;
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
    		$result['message'] = $resultPayment['message'];
    		return $result;
    	}

    	$payment = $resultPayment['item'];
    	//

    	$result['success'] = true;
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
    public function pay(
                        $changerId,
                        $userId,
                        $orderId,
                        $pgType,
                        $paymentMethodId,
                        $immediate
                        )
    {
        $result = [ 'success' => false ];
        try {
            $resultPayment = 
                $this->post('payment', [    
                    'changer_id' => $changerId,
                    'user_id' => $userId,
                    'order_id' => $orderId,
                    'pg_type' => $pgType,
                    'credit_card_id' => $paymentMethodId,
                    'immediate' => $immediate
                ]);

        } catch (ResponseException $e) {

            return $e->getMessage();

            $result['success'] = false;
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
    		$result['message'] = json_decode($e->getMessage(), true)['error']['message'];

    		return $result;
    	}

    	$result['success'] = true;
    	$result['item'] = $resultCreditCard['success']['data'];

    	return $result;
    }
}