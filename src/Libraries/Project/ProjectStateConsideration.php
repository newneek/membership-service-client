<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStateConsideration implements ProjectState
{
    public $nextStates = [ PublyContentService::PROJECT_STATUS_PREORDER, 
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
