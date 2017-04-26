<?php

namespace Publy\ServiceClient\Libraries\Project;

use Publy\ServiceClient\Libraries\Exceptions\ProjectStateException;
use Publy\ServiceClient\Libraries\Events\ProjectStateChanged;
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

    public function currentState()
    {
        return $this->currentState;
    }

    public function changeState($changerId, $status)
    {
    	if ($this->currentState->canStatusExit($status) == false) 
    	{
    		throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, "Cannot change project status (current: {$this->project['status']} change: {$status})");
    	}

        $nextState = $this->states[$status];
        if ($nextState->canStatusEnter($this->project) == false) 
        {
            throw new ProjectStateException(ProjectStateException::UNCHANGEABLE_STATUS, "Cannot change project status (current: {$this->project['status']} change: {$status})");
        }

        $oldStatus = $this->project['status'];

    	$this->currentState->onExit($this->project);
    	
        $event = new ProjectStateChanged($changerId, $this->project, $oldStatus, $status);
        event($event);

    	$this->currentState = $this->states[$status];

    	$this->currentState->onEnter($this->project);
    }
}
