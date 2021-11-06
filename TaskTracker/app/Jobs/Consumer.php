<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Consumer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $connection = new AMQPStreamConnection('cow.rmq2.cloudamqp.com', 5672, 'qrwsbgvf', 'AxvwwkqMp52baIbZ_wcE8Tan2TOyN2Jy', 'qrwsbgvf');
        $channel = $connection->channel();

        $channel->queue_declare('AccountsStream', false, false, false, false);

        $accountsStream = function ($msg) {
            $json = json_decode($msg->body);

            switch ($json->event) {
                case 'AccountsStream.Created': {
                    \App\Models\User::insert([
                        'id' => $json->payload->user->id,
                        'name' => $json->payload->user->name,
                        'email' => $json->payload->user->email,
                        'role' => 2
                    ]);
                    break;
                }
                case 'AccountsStream.Deleted': {
                    \App\Models\User::where('id', $json->payload->public_id)->delete();
                    break;
                }
                case 'AccountsStream.Updated': {
                    \App\Models\User::where('id', $json->payload->public_id)->update([
                        'role_id' => $json->payload->user->role_id
                    ])->delete();
                    break;
                }
            }

            \Log::info(['text' => ' [x] Received '. $msg->body. "\n"]);
        };
        
        $channel->basic_consume('AccountsStream', '', false, true, false, false, $accountsStream);
        
        while ($channel->is_open()) {
            $channel->wait();
        }
    }
}
