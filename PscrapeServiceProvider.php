<?php

namespace Pscrape\Pscrape;

use Illuminate\Support\ServiceProvider;

 
class PscrapeServiceProvider extends ServiceProvider {
    
    public function boot()
    {
        /*
        |--------------------------------------------------------------------------
        | Publish the Config file from the Package to the App directory
        |--------------------------------------------------------------------------
        */
        $this->configPublisher();
    }


    private function configPublisher()
    {
        // When users execute Laravel's vendor:publish command, the config file will be copied to the specified location
        $this->publishes([
            __DIR__.'/Config/pscrape.php' => config_path('pscrape.php'),
        ]);
    }
    