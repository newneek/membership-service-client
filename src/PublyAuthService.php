<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyAuthService extends BaseApiService {

    const GROUP_ADMIN = 1;
    const GROUP_MANAGER = 3;
    const GROUP_AUTHOR = 4;

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

    public function getUsersByIds($userIds)
    {
        return $this->get("user/by_ids", [ 'ids' => implode(',', $userIds) ]);
    }

    public function updateUser($changerId, $userId, $name, $email, $phone)
    {
        return $this->put("user/{$userId}", [ 'changer_id' => $changerId,
                                              'name' => $name,
                                              'email' => $email,
                                              'phone' => $phone ]);
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

    public function signup($userData)
    {
        return $this->post('signup', $userData);
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

    public function changeName($id, $newName)
    {
        $result = [ 'success' => false ];
        try {
            $this->post('change_name', array('id' => $id,
                                             'new_name' => $newName));
            $result['success'] = true;
        } catch (ResponseException $e) {
            $result['success'] = false;
            $result['message'] = json_decode($e->getMessage(), true)['error']['message'];
        }

        return $result;
    }

    public function changeReceiveEmail($id, $newReceiveEmail)
    {
        $result = [ 'success' => false ];
        try {
            $this->post('change_receive_email', array('id' => $id,
                                                      'receive_email' => $newReceiveEmail));
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
}