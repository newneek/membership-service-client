<?php

namespace Publy\ServiceClient\Libraries\Exceptions;

class ProjectStateException extends \Exception
{
    const UNCHANGEABLE_STATUS = 1;
    
    private $type;

    public function __construct($type, $message = null)
    {
        $this->type = $type;

        parent::__construct($message);
    }

    public function getType()
    {
        return $this->type;
    }
}
