<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{User, Credit, Debit, Audit};
use Carbon\Carbon;

class CloseDayBilling implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $date;

    public function __construct()
    {
        $this->date = Carbon::now()->format('Y-m-d');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach (User::get() as $user)
        {
            $credit = Credit::whereDate('created_at', '=', $this->date);
            $debit = Debit::whereDate('created_at', '=', $this->date);
            $balance = Debit::whereDate('created_at', '=', $this->date)->sum('ammount') - Credit::whereDate('created_at', '=', $this->date)->sum('ammount');

            // send to email

            $credit->update(['closed' => 1]);
            $debit->update(['closed' => 1]);
            Audit::create([
                'user_id' => $credit->first()->user_id,
                'body' => 'Выплачена сумма по окончанию дня - '.$balance
            ]);
        }
    }
}
