<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStateConsideration implements ProjectState
{
    public function onEnter()
    {
        
    }

    public function canStatusChange($status)
    {
        if ($status == PublyContentService::PROJECT_STATUS_PREORDER
            || $status == PublyContentService::PROJECT_STATUS_DROP) {
            return true;
        }

        return false;
    }

    public function onExit()
    {

    }
}
