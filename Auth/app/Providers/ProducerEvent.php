<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ProduceEvent\Producer;
use App\Services\ProduceEvent\LogProducer;

class ProducerEvent extends ServiceProvider
{
    public function register()
    {
        $config_provider = config('app.producer_event', LogProducer::class);
        $this->app->singleton(Producer::class, function ($app) use ($config_provider) {
            return new $config_provider();
        });
    }
}
