<?php

namespace App\ConsumerAction;

use App\Models\{Task, Credit, Audit};
use App\Jobs\RecalcBalance;

class TaskAssigned implements IConsumerAction
{
    private $task;

    public function __construct(int $task_id)
    {
        $this->task = Task::find($task_id);
        if ($this->task === null) {
            throw new \RuntimeException("Task is not found in system");
        }
    }
    
    public function handle() : bool
    {
        $event = [
            'user_id' => $this->task->user_id,
            'fee' => $this->task->fee
        ];

        if ($credit = Credit::make($this->task->user_id, $this->task->fee)) {
            Audit::log('Был создан расход на сумму '.$credit->fee);
            event(new RecalcBalance($credit->user_id));
        }

        return $credit != null;
    }
}