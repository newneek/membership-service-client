<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\PublyContentService;
use Publy\ServiceClient\Libraries\Events\ProjectPreorderSuccessModified;

class ProjectStatePreorderDone implements ProjectState
{
    public $nextStates = [
        PublyContentService::PROJECT_STATUS_SALES,
        PublyContentService::PROJECT_STATUS_DROP
    ];

    public $manuallyChangeableStates = [
        PublyContentService::PROJECT_STATUS_DROP
    ];

    public function onEnter($changerId, $project, $params)
    {
        if (isset($params['preorder_success'])) {
            // warning: this event update only DB, $project value not update yes (Do need this?)
            $event = new ProjectPreorderSuccessModified($changerId, $project['id'], $params['preorder_success']);
            event($event);
        }
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
