<?php

namespace App\Services\ProduceEvent;

use Log;

class LogProducer extends Producer
{
    public function publish($event)
    {
        Log::info(json_encode($event));
    }
}