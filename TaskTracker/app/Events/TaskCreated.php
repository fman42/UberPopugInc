<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;

    public function __construct($task)
    {
        $this->task = json_encode($task);
    }

    public function handle()
    {
        
    }
}
