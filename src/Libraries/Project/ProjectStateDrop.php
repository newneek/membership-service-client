<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStateDrop implements ProjectState
{
    public $nextStates = [];
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
