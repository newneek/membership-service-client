<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStatePaymentInProgress implements ProjectState
{
    public $nextStates = [ PublyContentService::PROJECT_STATUS_PREORDER_DONE, 
                            PublyContentService::PROJECT_STATUS_DROP ];
    public $manuallyChangeableStates = [];

    public function onEnter($changerId, $project, $params)
    {
    	
    }

    public function canStatusEnter($project)
    {
        return true;
    }

    public function canStatusExit($status)
    {
        return in_array($status, $this->nextStates);
    }

    public function onExit($project)
    {

    }
}
