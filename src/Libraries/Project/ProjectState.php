<?php

namespace Publy\ServiceClient\Libraries\Project;

interface ProjectState
{
    public function onEnter($project);
    public function canStatusEnter($project);
    public function canStatusExit($status);
    public function onExit($project);
}
