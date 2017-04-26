<?php

namespace Publy\ServiceClient\Libraries\Events;

use Publy\ServiceClient\Libraries\Events\Event;

class ProjectPreorderStarted extends Event
{
    public $project;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($project)
    {
    	$this->project = $project;
    }
}
