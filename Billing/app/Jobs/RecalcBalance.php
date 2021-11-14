<?php

namespace App\Jobs;

use App\Models\{User, Debit, Credit};
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalcBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $balance = Debit::whereDate('created_at', '=', $this->date)->sum('ammount') - Credit::whereDate('created_at', '=', $this->date)->sum('ammount');
        User::where('id', $this->user_id)->update([
            'balance' => $balance
        ]);
    }
}
