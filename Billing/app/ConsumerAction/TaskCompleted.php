<?php

namespace App\ConsumerAction;

use App\Models\{Task, Debit, Audit};

class TaskCompleted implements IConsumerAction
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

        if ($debit = Debit::make($this->task->user_id, $this->task->fee)) {
            Audit::log('Был создан дебит на сумму '.$debit->fee);
        }

        return $debit != null;
    }
}