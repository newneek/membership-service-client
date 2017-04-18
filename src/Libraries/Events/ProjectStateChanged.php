<?php

namespace Publy\ServiceClient\Libraries\Events;

use Publy\ServiceClient\Libraries\Events\Event;

class ProjectStateChanged extends Event
{
	public $project;
    public $oldValue;
    public $newValue;
	
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($project, $oldValue, $newValue)
    {
    	$this->project = $project;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }
}
