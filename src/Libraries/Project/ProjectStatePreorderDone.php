<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStatePreorderDone implements ProjectState
{
    public function onEnter()
    {
    	
    }

    public function canStatusChange($status)
    {
        if ($status == PublyContentService::PROJECT_STATUS_SALES
            || $status == PublyContentService::PROJECT_STATUS_DROP) {
            return true;
        }

        return false;
    }

    public function onExit()
    {

    }
}
