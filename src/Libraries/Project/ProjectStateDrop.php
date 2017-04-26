<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStateDrop implements ProjectState
{
    public $nextStates = [];

    public function onEnter($project)
    {
    	
    }

    public function canStatusChange($status)
    {
        return in_array($status, $this->nextStates);
    }

    public function onExit($project)
    {

    }
}
