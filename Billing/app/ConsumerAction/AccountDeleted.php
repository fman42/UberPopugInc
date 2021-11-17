<?php

namespace App\ConsumerAction;

use App\Models\User;

class AccountDeleted implements IConsumerAction
{
    private $user_id;

    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }
    
    public function handle() : bool
    {
        return User::find($this->user_id)->delete();
    }
}