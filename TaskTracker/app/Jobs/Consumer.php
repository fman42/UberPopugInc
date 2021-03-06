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
use App\Services\SchemaRegistry\ValidatorSchemaRegistry;

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
        $connection = new AMQPStreamConnection('cow.rmq2.cloudamqp.com', 5672, 'qrwsbgvf', config('app.rabbitmq.password'), 'qrwsbgvf');
        $channel = $connection->channel();

        $channel->queue_declare('AccountsStream', false, false, false, false);

        $accountsStream = function ($msg) {
            $json = json_decode($msg->body);
            $event = json_decode($msg->body, true);

            switch ($json->event) {
                case 'AccountsStream.Created': {
                    if (!ValidatorSchemaRegistry::check($event, 'Auth', 'AccountCreated')) {
                        break;
                    }

                    \App\Models\User::insert([
                        'id' => $json->payload->user->id,
                        'name' => $json->payload->user->name,
                        'email' => $json->payload->user->email,
                        'role' => 2
                    ]);
                    break;
                }
                case 'AccountsStream.Deleted': {
                    if (!ValidatorSchemaRegistry::check($event, 'Auth', 'AccountDeleted')) {
                        break;
                    }
                    \App\Models\User::where('id', $json->payload->public_id)->delete();
                    break;
                }
                case 'AccountsStream.Updated': {
                    if (!ValidatorSchemaRegistry::check($event, 'Auth', 'AccountUpdated')) {
                        break;
                    }
                    \App\Models\User::where('id', $json->payload->public_id)->update([
                        'role' => $json->payload->user->role_id
                    ]);
                    break;
                }
            }

            \Log::info(['text' => ' [x] Received '. $msg->body. "\n"]);
        };
        
        $channel->basic_consume('AccountsStream', '', false, false, false, false, $accountsStream);
        while ($channel->is_open()) {
            $channel->wait();
        }
    }
}
