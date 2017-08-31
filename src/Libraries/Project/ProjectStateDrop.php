<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;
use Publy\ServiceClient\Libraries\Events\ProjectWasDroped;

class ProjectStateDrop implements ProjectState
{
    public $nextStates = [];
    public $manuallyChangeableStates = [];

    public function onEnter($changerId, $project, $params)
    {
        $event = new ProjectWasDroped($changerId, $project);
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
