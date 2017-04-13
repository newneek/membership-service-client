<?php

namespace Publy\ServiceClient\Libraries\Project;

interface ProjectState
{
    public function onEnter();
    public function canStatusChange($status);
    public function onExit();
}
