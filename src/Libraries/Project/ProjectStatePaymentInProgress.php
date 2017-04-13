<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStatePaymentInProgress implements ProjectState
{
    public function onEnter()
    {
    	
    }

    public function canStatusChange($status)
    {
        if ($status == PublyContentService::PROJECT_STATUS_PREORDER_DONE
            || $status == PublyContentService::PROJECT_STATUS_DROP) {
            return true;
        }

        return false;
    }

    public function onExit()
    {

    }
}
