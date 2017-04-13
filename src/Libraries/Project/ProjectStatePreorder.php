<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStatePreorder implements ProjectState
{
    public $nextStates = [ PublyContentService::PROJECT_STATUS_PAYMENT_IN_PROGRESS, 
                            PublyContentService::PROJECT_STATUS_DROP ];

    public function onEnter()
    {
    	
    }

    public function canStatusChange($status)
    {
        return in_array($status, $this->nextStates);
    }

    public function onExit()
    {

    }
}
