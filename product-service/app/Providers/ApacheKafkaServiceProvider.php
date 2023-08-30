<?php

namespace App\Providers;

use App\Kafka\KafkaConnection;
use Illuminate\Support\ServiceProvider;

class ApacheKafkaServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $manager = $this->app['queue'];
        $manager->addConnector('kafka', function(){
            return new KafkaConnection();
        });
    }
}
