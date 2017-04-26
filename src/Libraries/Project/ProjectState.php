<?php

namespace Publy\ServiceClient\Libraries\Project;

interface ProjectState
{
    public function onEnter($project);
    public function canStatusChange($status);
    public function onExit($project);
}
