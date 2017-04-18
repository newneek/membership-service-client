<?php

namespace Publy\ServiceClient\Libraries\Events;

use Publy\ServiceClient\Libraries\Events\Event;

class ProjectStateChanged extends Event
{
    public $changerId;
	public $project;
    public $oldValue;
    public $newValue;
	
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($changerId, $project, $oldValue, $newValue)
    {
        $this->changerId = $changerId;
    	$this->project = $project;
        $this->oldValue = $oldValue;
        $this->newValue = $newValue;
    }
}
