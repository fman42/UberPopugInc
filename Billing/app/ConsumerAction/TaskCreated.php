<?php

namespace App\ConsumerAction;

use App\Models\Task;

class TaskCreated implements IConsumerAction
{
    private $event;

    public function __construct(array $taskEvent)
    {
        $this->event = $taskEvent;
    }
    
    public function handle() : bool
    {
        $task = Task::create($this->event);
        if ($task) {
            $task->fee = mt_rand(-10, -20);
            $task->ammount = mt_rand(20, 40);
            $task->save();
        }

        return $task != null;
    }
}