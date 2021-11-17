<?php

namespace App\ConsumerAction;

use App\Models\Audit;

class MakeCloseWorkDayAudit implements IConsumerAction
{
    private $user_id;

    private $ammount;

    public function __construct(int $user_id, int $ammount)
    {
        $this->user_id = $user_id;
        $this->ammount = $ammount;
    }
    
    public function handle() : bool
    {
        $record = Audit::create([
            'user_id' => $this->user_id,
            'body' => sprintf('Была выплачена сумму в размере %d $', $this->ammount)
        ]);

        return $record != null;
    }
}