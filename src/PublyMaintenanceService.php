<?php

namespace Publy\ServiceClient;

use Publy\ServiceClient\Api\BaseApiService;
use Publy\ServiceClient\Api\ResponseException;

class PublyMaintenanceService extends BaseApiService
{

    public function __construct($domain)
    {
        parent::__construct();

        $this->domain = $domain;
        $this->apiUrl = "$this->domain/";
    }

    public function getMaintenance()
    {
        return $this->get('maintenance');
    }

    public function updateMaintenance($changerId, $isActive, $description, $retryAfter)
    {
        $result = [ 'success' => false ];
        try {
            $result = $this->post('maintenance', [
                'changer_id' => $changerId,
                'is_active' => $isActive,
                'description' => $description,
                'retry_after' => $retryAfter
            ]);
        } catch (ResponseException $e) {
            $result = [ 'success' => false,
                        'error_code' => $e->getCode(),
                        'message' => $e->getMessage()
                        ];
        }

        return $result;
    }

    public function getExceptionIps()
    {
        return $this->get('exception_ips');
    }

    public function storeExceptionIp($changerId, $ip, $description)
    {
        $result = [ 'success' => false ];
        try {
            $result = $this->put('exception_ip', [
                'changer_id' => $changerId,
                'ip' => $ip,
                'description' => $description
            ]);
        } catch (ResponseException $e) {
            $result = [ 'success' => false,
                        'error_code' => $e->getCode(),
                        'message' => $e->getMessage()
                        ];
        }

        return $result;
    }
    
    public function updateExceptionIp($changerId, $id, $params)
    {
        $params['id'] = $id;
        $params['changer_id'] = $changerId;

        $result = [ 'success' => false ];
        try {
            $result = $this->post('exception_ip', $params);
        } catch (ResponseException $e) {
            $result = [ 'success' => false,
                        'error_code' => $e->getCode(),
                        'message' => $e->getMessage()
                        ];
        }

        return $result;
    }
}
