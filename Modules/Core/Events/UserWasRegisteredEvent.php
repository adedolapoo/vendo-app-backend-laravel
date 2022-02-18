<?php
namespace Modules\Core\Events;
use Illuminate\Queue\SerializesModels;

class UserWasRegisteredEvent extends Event
{
    use SerializesModels;
}