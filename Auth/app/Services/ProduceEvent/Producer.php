<?php

namespace App\Services\ProduceEvent;

abstract class Producer
{
    public function makeEvent($eventName, $payload)
    {
        $this->publish([
            'event' => 'Auth.'.$eventName,
            'payload' => $payload
        ]);
    }

    public abstract function publish($event);
}