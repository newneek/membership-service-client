<?php

namespace Publy\ServiceClient\Libraries\Events;

use Publy\ServiceClient\Libraries\Events\Event;

class ProjectWasDroped extends Event
{
    public $changerId;
    public $project;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($changerId, $project)
    {
        $this->changerId = $changerId;
        $this->project = $project;
    }
}
