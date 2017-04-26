<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;
use Publy\ServiceClient\Libraries\Events\ProjectPreorderStarted;

class ProjectStatePreorder implements ProjectState
{
    public $nextStates = [ PublyContentService::PROJECT_STATUS_PAYMENT_IN_PROGRESS, 
                            PublyContentService::PROJECT_STATUS_DROP ];

    public function onEnter($project)
    {
    	$event = new ProjectPreorderStarted($project);
        event($event);
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
