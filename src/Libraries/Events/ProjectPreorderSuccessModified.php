<?php

namespace Publy\ServiceClient\Libraries\Events;

use Publy\ServiceClient\Libraries\Events\Event;

class ProjectPreorderSuccessModified extends Event
{
    public $changerId;
    public $projectId;
    public $flagSuccess;
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($changerId, $projectId, $flagSuccess)
    {
        $this->changerId = $changerId;
        $this->projectId = $projectId;
    	$this->flagSuccess = $flagSuccess;
    }
}
