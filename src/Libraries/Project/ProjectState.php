<?php

namespace Publy\ServiceClient\Libraries\Project;

interface ProjectState
{
    public function onEnter($changerId, $project, $params);
    public function canStatusEnter($project);
    public function canStatusExit($status);
    public function onExit($project);
}
