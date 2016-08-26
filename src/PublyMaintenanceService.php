<?php

namespace Publy\ServiceClient;

use App\Services\Api\BaseApiService;
use App\Services\Api\ResponseException;

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
        return $this->get('exception_ip');
    }

    public function updateExceptionIps($changerId, $ips)
    {
        $result = [ 'success' => false ];
        try {
            $result = $this->post('exception_ip', [
                'changer_id' => $changerId,
                'ips' => $ips
            ]);
        } catch (ResponseException $e) {
            $result = [ 'success' => false,
                        'error_code' => $e->getCode(),
                        'message' => $e->getMessage()
                        ];
        }

        return $result;
    }
}
