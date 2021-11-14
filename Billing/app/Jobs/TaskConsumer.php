<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Credit, Debit, Task, Audit};

class TaskConsumer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $event = [];
        $event_name = 'Task.Assigned';
        switch ($event_name)
        {
            case 'TaskStream.Created':
            {
                Task::create($event);
                break;
            }
            case 'Task.Assigned':
            {
                $event = [
                    'user_id' => $event->assigned_user_id,
                    'fee' => mt_rand(-10, -20)
                ];
                Credit::make($event->assigned_user_id, $event['fee']);
                Audit::log('Был создан расход на сумму '.$event['fee']);
                $this->producer->makeEvent($event);
                break;
            }
            case 'Task.Completed':
            {
                $event = [
                    'user_id' => $event->assigned_user_id,
                    'fee' => mt_rand(20, 40)
                ];
                Debit::make($event->assigned_user_id, $event['fee']);
                Audit::log('Был создан дебит на сумму '.$event['fee']); 
                $this->producer->makeEvent($event);
                break;
            }
        }
    }
}
