<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\Libraries\Exceptions\ProjectStateException;

use Publy\ServiceClient\Libraries\Project\ProjectStateConsideration;
use Publy\ServiceClient\Libraries\Project\ProjectStatePreorder;
use Publy\ServiceClient\Libraries\Project\ProjectStatePaymentInProgress;
use Publy\ServiceClient\Libraries\Project\ProjectStatePreorderDone;
use Publy\ServiceClient\Libraries\Project\ProjectStateSales;
use Publy\ServiceClient\Libraries\Project\ProjectStateDrop;

use Publy\ServiceClient\PublyContentService;

class ProjectStateMachine
{
	private $project;

	private $states;
	private $currentState;

    public function __construct($project)
    {
    	$this->project = $project;

    	$this->initState();
    }

    public function initState()
    {
    	$this->states = [
    		publyContentService::PROJECT_STATUS_UNDER_CONSIDERATION => new ProjectStateConsideration(),
    		publyContentService::PROJECT_STATUS_PREORDER => new ProjectStatePreorder(),
    		publyContentService::PROJECT_STATUS_PAYMENT_IN_PROGRESS => new ProjectStatePaymentInProgress(),
    		publyContentService::PROJECT_STATUS_PREORDER_DONE => new ProjectStatePreorderDone(),
    		publyContentService::PROJECT_STATUS_SALES => new ProjectStateSales(),
    		publyContentService::PROJECT_STATUS_DROP => new ProjectStateDrop(),
    	];

    	$this->currentState = $this->states[$this->project['status']];
    }

    public function changeState($status)
    {
    	if ($this->currentState->canStatusChange($status) == false) 
    	{
    		throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, "Cannot change project status (current: {$this->project['status']} change: {$status})");
    	}

    	$this->currentState->onExit();
    	
        // TODO : send event
    	// $this->project->update(['status' => $status]);
    	$this->currentState = $this->states[$status];

    	$this->currentState->onEnter();
    }
}
