<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyAuthService extends BaseApiService {

    const GROUP_ADMIN = 1;
    const GROUP_NORMAL = 2;
    const GROUP_MANAGER = 3;
    const GROUP_AUTHOR = 4;
    const GROUP_EDITOR = 5;
    const GROUP_CURATOR = 6;
    const GROUP_MAX = 7;

    const DELETE_REQUESTED_USER_REQUEST_STATUS_DELETE_REQUESTED = 1; // 탈퇴 요청
    const DELETE_REQUESTED_USER_REQUEST_STATUS_DELETE_COMPLETE = 2; // 탈퇴 완료 (유저 삭제 완료)

    const STRING_GROUP = [
        PublyAuthService::GROUP_ADMIN => "최고관리자",
        PublyAuthService::GROUP_NORMAL => "일반회원",
        PublyAuthService::GROUP_MANAGER => "관리자",
        PublyAuthService::GROUP_AUTHOR => "저자",
        PublyAuthService::GROUP_EDITOR => "에디터",
        PublyAuthService::GROUP_CURATOR => "뉴스 큐레이터"
    ];

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    /*
     * User Related Interfaces
     */
    public function getUser($userId, $filterArray = [])
    {
        return $this->get("user/{$userId}", $filterArray);
    }

    public function getAuthUser($userId, $filterArray = [])
    {
        return $this->getUser($userId, array_merge($filterArray, ['use_auth_only' => true]));
    }

    public function getUsers($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("user", $filterArray);
    }

    public function getAuthUsers($page = 1, $limit = 10, $filterArray = [])
    {
        return $this->getUsers($page, $limit, array_merge($filterArray, ['use_auth_only' => true]));
    }

    public function getUsersByIds($userIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $userIds);

        return $this->get("user/by_ids", $filterArray);
    }

    public function getAuthUsersByIds($userIds, $filterArray = [])
    {
        return $this->getUsersByIds($userIds, array_merge($filterArray, ['use_auth_only' => true]));
    }

    public function updateUser($changerId, $userId, $name, $email, $phone)
    {
        return $this->put("user/{$userId}", [ 'changer_id' => $changerId,
                                              'name' => $name,
                                              'email' => $email,
                                              'phone' => $phone ]);
    }

    public function updateUser2($changerId, $userId, $name, $email, $phone)
    {
        $inputs = [];
        if ($name !== null) {
            $inputs['name'] = $name;
        }

        if ($email !== null) {
            $inputs['email'] = $email;
        }

        if ($phone !== null) {
            $inputs['phone'] = $phone;
        }

        return $this->put("user/{$userId}", array_merge([ 'changer_id' => $changerId], $inputs));
    }

    public function updateUser3($changerId, $userId, $name, $email, $phone, $groups)
    {
        $inputs = [];
        if ($name !== null) {
            $inputs['name'] = $name;
        }

        if ($email !== null) {
            $inputs['email'] = $email;
        }

        if ($phone !== null) {
            $inputs['phone'] = $phone;
        }

        if ($groups !== null) {
            $inputs['groups'] = $groups;
        }

        return $this->put("user/{$userId}", array_merge([ 'changer_id' => $changerId], $inputs));
    }

    public function updateUser4($changerId,
                                $userId,
                                $name,
                                $email,
                                $password,
                                $passwordConfirm,
                                $phone,
                                $groups,
                                $linkUrls,
                                $imageUrl)
    {
        $inputs = ['changer_id' => $changerId,
                   'name' => $name,
                   'email' => $email,
                   'password' => $password,
                   'password_confirmation' => $passwordConfirm,
                   'phone' => $phone,
                   'groups' => $groups,
                   'link_urls' => $linkUrls,
                   'image_url' => $imageUrl
        ];

        return $this->put("user/{$userId}", $inputs);
    }

    public function updateUserMarketingAgree($changerId, $userId, $margetingEmailAgree)
    {
        $inputs = [
            'changer_id' => $changerId,
            'marketing_email_agree' => $margetingEmailAgree
        ];

        return $this->put("user/{$userId}", $inputs);
    }

    public function updateUserPushNotificaionAgree($changerId, $userId, $pushNotificationAgree)
    {
        $inputs = [
            'changer_id' => $changerId,
            'push_notification_agree' => $pushNotificationAgree
        ];

        return $this->put("user/{$userId}", $inputs);
    }

    public function deleteUser($changerId, $userId)
    {
        return $this->post("user/{$userId}/delete", [ 'changer_id' => $changerId ]);
    }

    /*
     * User Email Change History Related Interfaces
     */

    public function getUserEmailChangeHistoryByFrom($fromEmail)
    {
        return $this->get("user_email_change_history/from/{$fromEmail}");
    }

    public function getUserByEmailDisagreeToken($token)
    {
        return $this->get("user/email_disagree_token/{$token}");
    }

    public function getAllUserIds()
    {
        return $this->get("user/all_user_ids");
    }

    public function retrieveById($id)
    {
    	return $this->get('retrieve_by_id', array('id'=>$id));
    }

    /**
     * @param $id
     * @param $token
     * @return mixed
     * @throws ResponseException
     */
    public function retrieveByToken($id, $token)
    {
    	return $this->get('retrieve_by_token', array('id'=> $id,
    											     'token' => $token));
    }

    public function retrieveByTokenOnlyUser($id, $token)
    {
        return $this->get('retrieve_by_token', array(
            'id' => $id,
            'token' => $token,
            'with_group' => 0,
            'use_auth_only' => true,
        ));
    }

    public function retrieveByPasswordToken($id, $passwordToken)
    {
        return $this->get('retrieve_by_password_token', array('id' => $id,
                                                              'password_token' => $passwordToken));
    }

    public function updateRememberToken($id, $token)
    {
    	return $this->post('update_remember_token', array('id'=> $id,
    	 											      'token' => $token));
    }

    public function deleteUserRememberToken($id, $token)
    {
        return $this->post('delete_remember_token', array('id'=> $id,
            'token' => $token));
    }

    public function retrieveByEmail($email)
    {
        return $this->get('retrieve_by_email', array('email'=> $email));
    }

    public function retrieveByFacebookToken($accessToken)
    {
        return $this->get('retrieve_by_facebook_token', array('access_token' => $accessToken));
    }

    public function retrieveByCode($code)
    {
        return $this->get('retrieve_by_code', array('code'=> $code));
    }

    public function overwriteUserByFacebookToken($accessToken)
    {
        return $this->post('overwrite_user_by_facebook_token', array('access_token' => $accessToken));
    }

    public function validateCredentials($id, $password)
    {
    	return $this->post('validate_credentials', array('id'=> $id,
    												     'password' => $password));
    }

    public function signup($changerId, $name, $email, $password, $subscribeToWeeklyLetter = 0, $sendMail = 1)
    {
        return $this->post("signup", [
            'changer_id' => $changerId,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'subscribe_to_weekly_letter' => $subscribeToWeeklyLetter,
            'send_mail' => $sendMail
        ]);
    }

    public function signup2($changerId, $name, $email, $password, $subscribeToWeeklyLetter, $margetingEmailAgree, $product = 'membership')
    {
        return $this->post("signup", [ 'changer_id' => $changerId,
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'subscribe_to_weekly_letter' => $subscribeToWeeklyLetter,
            'marketing_email_agree' => $margetingEmailAgree,
            'product' => $product
        ]);
    }

    public function signupByFacebookToken($accessToken, $ipAddress)
    {
        return $this->post('signup_by_facebook_token', array('access_token' => $accessToken,
                                                             'ip_address' => $ipAddress));
    }

    public function createUser($changerId, $name)
    {
        return $this->post('create_user', [
            'name' => $name, 'changer_id' => $changerId
        ]);
    }

    public function createPartnerUser($changerId, $partnerUserId, $userId, $planId)
    {
        return $this->post('partner_user', [
            'changer_id' => $changerId,
            'partner_user_id' => $partnerUserId,
            'user_id' => $userId,
            'plan_id' => $planId
        ]);
    }

    public function changePassword($id, $currentPassword, $newPassword)
    {
        $result = [ 'success' => false ];
        try {
            $this->post('change_password', array('id' => $id,
                                                 'current_password' => $currentPassword,
                                                 'new_password' => $newPassword));
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function createNewPassword($userId, $newPassword, $newPasswordConfirm)
    {
        $inputs = [
            "user_id" => $userId,
            "new_password" => $newPassword,
            "new_password_confirm" => $newPasswordConfirm,
        ];

        return $this->post('create_new_password', $inputs);
    }

    public function forgotPassword($email)
    {
        return $this->post('forgot_password', array('email' => $email));
    }

    public function resetPassword($id, $passwordToken, $newPassword)
    {
        return $this->post('reset_password', array('id' => $id,
                                                   'password_token' => $passwordToken,
                                                   'new_password' => $newPassword));
    }

    public function createUserLoginHistory($userId, $ipAddress, $os, $browser, $deviceId)
    {
        return $this->post('user_login_history', array( 'user_id' => $userId,
                                                        'ip_address' => $ipAddress,
                                                        'os' => $os,
                                                        'browser' => $browser,
                                                        'device_id' => $deviceId ));
    }

    public function userLogin($userId, $ipAddress, $os, $browser, $deviceId, $method, $product = 'membership')
    {
        return $this->post('user_login', [
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'os' => $os,
            'browser' => $browser,
            'device_id' => $deviceId,
            'method' => $method,
            'product' => $product
        ]);
    }

    public function getPartnerUser($partnerUserId)
    {
        return $this->get("/partner_user/partner_user_id/{$partnerUserId}");
    }

    public function getPartnerUserByUser($userId)
    {
        return $this->get("/partner_user/user/{$userId}");
    }

    public function getPartnerUsers($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("/partner_user", $filterArray);
    }

    public function syncOneSignalData()
    {
        return $this->post("/sync");
    }

    public function retrieveBySocialLogin($socialType, $socialUserId)
    {
        return $this->get('retrieve_by_social_login',
            array('social_type' => $socialType, 'social_user_id' => $socialUserId)
        );
    }

    public function signupByKakaoId($kakaoUserId, $username, $email, $phone)
    {
        return $this->post('signup_by_kakao', array(
            'kakao_user_id' => $kakaoUserId,
            'username' => $username,
            'email' => $email,
            'phone' => $phone,
        ));
    }

    public function signupByAppleId($appleUserId, $username, $email)
    {
        return $this->post('signup_by_apple', array(
            'apple_user_id' => $appleUserId,
            'username' => $username,
            'email' => $email,
        ));
    }

    public function showSocialLoginTypes($userId)
    {
        return $this->get("user/{$userId}/social_login_types");
    }

    public function initializeUserNotificationStatus($userId)
    {
        return $this->put("user/{$userId}/initialize_notification_status");
    }

    public function getUserNotificationStatus($userId, $filterArray = [])
    {
        return $this->get("user/{$userId}/notification_status", $filterArray);
    }

    public function updateOrCreateUserNotificationStatus($userId, $notificationStatus)
    {
        return $this->put("user/{$userId}/notification_status", ['notification_status' => $notificationStatus]);
    }

    public function checkPasswordModifyAllowed($userId)
    {
        return $this->get("user/{$userId}/check_password_modify_allowed");
    }

    public function findUserNotificationStatus($filterArray = [])
    {
        return $this->get("user_notification_status", $filterArray);
    }

    public function findUserDevice($userId, $deviceId, $filterArray = [])
    {
        $filterArray['user_id'] = $userId;
        $filterArray['device_id'] = $deviceId;
        return $this->get("user_device", $filterArray);
    }

    public function findUserDeviceByUserId($userId, $filterArray = [])
    {
        $filterArray['user_id'] = $userId;
        return $this->get("user_device", $filterArray);
    }

    public function updateOrCreateUserDevice($userId, $deviceId, $params = [])
    {
        $params['user_id'] = $userId;
        $params['device_id'] = $deviceId;
        return $this->put("user_device", $params);
    }

    public function updateUserDeviceLastUsedAt($userId, $deviceId)
    {
        $filterArray = [];
        $filterArray['user_id'] = $userId;
        $filterArray['device_id'] = $deviceId;
        return $this->put("user_device/last_used_at", $filterArray);
    }

    public function activateUserDevice($userId, $deviceId, $params = [])
    {
        $params['user_id'] = $userId;
        $params['device_id'] = $deviceId;
        return $this->put("user_device/activate", $params);
    }

    public function getDeleteRequestedUsers($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("delete_requested_user", $filterArray);
    }

    public function createDeleteRequestedUser($changerId, $userId, $requestStatus)
    {
        return $this->post("delete_requested_user", [
            'changer_id' => $changerId,
            'user_id' => $userId,
            'request_status' => $requestStatus,
        ]);
    }

    public function updateDeleteRequestedUser($changerId, $deleteRequestedUserId, $userId, $requestStatus = null, $reason = null)
    {
        $inputs = [
            'changer_id' => $changerId,
            'user_id' => $userId
        ];

        if ($requestStatus) {
            $inputs['request_status'] = $requestStatus;
        }
        if ($reason) {
            $inputs['reason'] = $reason;
        }

        return $this->patch("delete_requested_user/{$deleteRequestedUserId}", $inputs);
    }

    public function deleteDeleteRequestedUser($changerId, $deleteRequestedUserId)
    {
        return $this->post("delete_requested_user/{$deleteRequestedUserId}/delete", ['changer_id' => $changerId]);
    }
}
