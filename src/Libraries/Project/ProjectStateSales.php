<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\Libraries\Exceptions\ProjectStateException;
use Publy\ServiceClient\PublyContentService;

class ProjectStateSales implements ProjectState
{
    public $nextStates = [ PublyContentService::PROJECT_STATUS_PREORDER_DONE, 
                           PublyContentService::PROJECT_STATUS_DROP ];
    public $manuallyChangeableStates = [ PublyContentService::PROJECT_STATUS_DROP ];

    public function onEnter($changerId, $project, $params)
    {
    	
    }

    public function canStatusEnter($project)
    {
        if ($project['type'] === PublyContentService::PROJECT_TYPE_BUNDLE && !$project['guide_id']) {
            throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, '가이드를 등록해야 합니다.');
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
