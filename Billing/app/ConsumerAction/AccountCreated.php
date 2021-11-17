<?php

namespace App\ConsumerAction;

use App\Models\User;

class AccountCreated implements IConsumerAction
{
    private $event;

    public function __construct(array $taskEvent)
    {
        $this->event = $taskEvent;
    }
    
    public function handle() : bool
    {
        $user = User::create($this->event);
        return $user != null;
    }
}