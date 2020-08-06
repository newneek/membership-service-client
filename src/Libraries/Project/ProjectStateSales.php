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
        // 리워드 - 리워드가 1개만 남아 있어야 함
        $count = 0;
        foreach ($project['rewards'] as $reward) {
            if ($reward['is_active']) {
                $count++;
                if ($reward['has_offline']) {
                    throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, '즉시구매 상품은 오프라인 행사를 포함하지 않습니다.');
                }
            }
        }

        if ($count != 1) {
            throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, '즉시구매는 상품이 1개여야 합니다.');
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
