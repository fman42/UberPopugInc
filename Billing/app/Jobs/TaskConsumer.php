<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\ConsumerAction\{TaskAssigned, TaskCompleted, TaskCreated};

class TaskConsumer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $event = [];
        $event_name = 'Task.Assigned';
        switch ($event_name)
        {
            case 'TaskStream.Created':
            {
                (new TaskCreated($event))->handle();
                break;
            }
            case 'Task.Assigned':
            {   
                (new TaskAssigned((int) $event['task_id']))->handle();
                break;
            }
            case 'Task.Completed':
            {
                (new TaskCompleted((int) $event['task_id']))->handle();
                break;
            }
        }
    }
}
