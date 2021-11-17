<?php

namespace App\ConsumerAction;

use App\Models\User;

class AccountUpdated implements IConsumerAction
{
    private $user_id;

    private $data;

    public function __construct(string $user_id, array $eventData)
    {
        $this->user_id = $user_id;
        $this->data = $eventData;
    }
    
    public function handle() : bool
    {
        return User::find($this->user_id)->update($this->data);
    }
}