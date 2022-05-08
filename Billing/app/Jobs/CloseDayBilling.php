<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\{Credit, Debit};
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

    private $user_id;

    public function __construct(?string $date, int $user_id)
    {
        $this->date = $date === null ? Carbon::now()->format('Y-m-d') : Carbon::parse($date)->format('Y-m-d');
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $creditQuery = Credit::where([
            'user_id' => $this->user_id,
        ])->toDay();

        $debitQuery = Debit::where([
            'user_id' => $this->user_id,
        ])->toDay();

        $currentWithdrawn = $debitQuery->sum('ammount') - $creditQuery->sum('ammount');
        $creditQuery->update(['closed' => 1]);
        $debitQuery->update(['closed' => 1]);
        
        $this->sendEvent($currentWithdrawn);
    }

    private function sendEvent(int $ammount)
    {
        $eventSchema = [
            'user_id' => $this->user_id,
            'ammount' => $ammount
        ];

        // Check schema registry
        $this->producer->makeEvent('Billing', 'ClosedDayBilling', $eventSchema);
    }
}
