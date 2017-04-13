<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;

class ProjectStateDrop implements ProjectState
{
    public function onEnter()
    {
    	
    }

    public function canStatusChange($status)
    {
        return false;
    }

    public function onExit()
    {

    }
}
