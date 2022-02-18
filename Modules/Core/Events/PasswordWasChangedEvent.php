<?php
namespace Modules\Core\Events;
use Illuminate\Queue\SerializesModels;

class PasswordWasChangedEvent extends Event
{
    use SerializesModels;
}