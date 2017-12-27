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
    const GROUP_MAX = 6;

    const STRING_GROUP = [
        PublyAuthService::GROUP_ADMIN => "최고관리자",
        PublyAuthService::GROUP_NORMAL => "일반회원",
        PublyAuthService::GROUP_MANAGER => "관리자",
        PublyAuthService::GROUP_AUTHOR => "저자",
        PublyAuthService::GROUP_EDITOR => "에디터"
    ];

    public function __construct($domain) {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";        
    }

    /*
     * User Related Interfaces
     */
    public function getUser($userId)
    {
        return $this->get("user/{$userId}");
    }

    public function getUsers($page = 1, $limit = 10, $filterArray = [])
    {
        $filterArray['page'] = $page;
        $filterArray['limit'] = $limit;
        return $this->get("user", $filterArray);
    }

    public function getUsersByIds($userIds, $filterArray = [])
    {
        $filterArray['ids'] = implode(',', $userIds);

        return $this->get("user/by_ids", $filterArray);
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

    public function retrieveById($id)
    {
    	return $this->get('retrieve_by_id', array('id'=>$id));
    }

    public function retrieveByToken($id, $token)
    {
    	return $this->get('retrieve_by_token', array('id'=> $id, 
    											     'token' => $token));
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

    public function overwriteUserByFacebookToken($accessToken)
    {
        return $this->post('overwrite_user_by_facebook_token', array('access_token' => $accessToken));
    }

    public function validateCredentials($id, $password)
    {
    	return $this->post('validate_credentials', array('id'=> $id, 
    												     'password' => $password));
    }

    public function signup($changerId, $name, $email, $password, $subscribeToWeeklyLetter = 0)
    {
        return $this->post("signup", [ 'changer_id' => $changerId,
                                       'name' => $name,
                                       'email' => $email,
                                       'password' => $password,
                                       'subscribe_to_weekly_letter' => $subscribeToWeeklyLetter]);
    }    

    public function signupByFacebookToken($accessToken, $ipAddress)
    {
        return $this->post('signup_by_facebook_token', array('access_token' => $accessToken,
                                                             'ip_address' => $ipAddress));
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

    public function createUserLoginHistory($userId, $ipAddress, $browser, $deviceId)
    {
        return $this->post('user_login_history', array( 'user_id' => $userId,
                                                        'ip_address' => $ipAddress,
                                                        'browser' => $browser,
                                                        'device_id' => $deviceId ));
    }
}