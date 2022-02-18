<?php
namespace Modules\Core\Events;

use Illuminate\Queue\SerializesModels;

class MailerEvent extends Event
{
    use SerializesModels;

}