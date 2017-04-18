<?php

namespace Publy\ServiceClient\Libraries\Events;

use Illuminate\Queue\SerializesModels;

abstract class Event
{
    use SerializesModels;
}
