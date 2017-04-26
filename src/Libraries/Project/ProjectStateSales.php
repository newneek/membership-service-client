<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\Libraries\Exceptions\ProjectStateException;
use Publy\ServiceClient\PublyContentService;

class ProjectStateSales implements ProjectState
{
    public $nextStates = [ PublyContentService::PROJECT_STATUS_PREORDER_DONE, 
                            PublyContentService::PROJECT_STATUS_DROP ];

    public function onEnter($project)
    {
    	
    }

    public function canStatusEnter($project)
    {
        $currDate = \Carbon\Carbon::now();
        $finishDate = new \Carbon\Carbon($project['preorder_finish_at']);

        if ($currDate->lt($finishDate)) {
            return false;
            // throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, 'project must be finished');
        }

        // 리워드 - 리워드가 1개만 남아 있어야 함
        $count = count($project['rewards']);
        if ($count != 1) {
            return false;
            // throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, 'project must have only one active reward');
        }

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
