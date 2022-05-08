<?php

namespace App\ConsumerAction;

use App\Models\User;
use App\Mail\ClosedWorkSession;
use Illuminate\Support\Facades\Mail;

class SendEmployeeCloseWorkDayAlert implements IConsumerAction
{
    private $user;

    public function __construct(int $user_id)
    {
        $this->user = User::find($user_id);
        if ($this->user === null) {
            throw new \RuntimeException("User is not found in system");
        }
    }
    
    public function handle() : bool
    {
        Mail::to($this->user->email)->queue(new ClosedWorkSession());

        return true;
    }
}