<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\ConsumerAction\{AccountCreated, AccountUpdated, AccountDeleted};

class AuthConsumer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $event = [];
        $eventName = '';
        switch ($eventName)
        {
            case 'AccountCreated':
            {
                (new AccountCreated($event))->handle();
                break;
            }
            case 'AccountDeleted':
            {
                (new AccountDeleted($event['user_id']))->handle();
                break;
            }
            case 'AccountUpdated':
            {
                (new AccountUpdated($event['user_id'], $event))->handle();
                break;
            }
        }
    }
}
