<?php

namespace App\Services\ProduceEvent;

abstract class Producer
{
    public function makeEvent($topicName, $eventName, $payload)
    {
        $this->publish($topicName, [
            'event' => $topicName.'.'.$eventName,
            'payload' => $payload
        ]);
    }

    public abstract function publish($topicName, $event);
}