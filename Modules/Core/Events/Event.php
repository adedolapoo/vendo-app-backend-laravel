<?php

namespace Modules\Core\Events;

abstract class Event
{
    public function __construct($data)
    {
        $this->data = $data;
    }
}
