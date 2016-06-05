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
    
    /**
     * Register the service provider.
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Implementation Bindings
        |--------------------------------------------------------------------------
        */
        $this->implementationBindings();

        /*
        |--------------------------------------------------------------------------
        | Facade Bindings
        |--------------------------------------------------------------------------
        */
        $this->facadeBindings();

        /*
        |--------------------------------------------------------------------------
        | Registering Service Providers
        |--------------------------------------------------------------------------
        */
        $this->serviceProviders();
    }

    /**
     * Facades Binding.
     */
    private function facadeBindings()
    {
        // Register 'pscrape.shorten' instance container
        $this->app['pscrape.scrape'] = $this->app->share(function ($app) {
            return $app->make('Pscrape\Pscrape\Scrape');
        });

        // Register 'Shorten' Alias, So users don't have to add the Alias to the 'app/config/app.php'
        $this->app->booting(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Scrape', 'Pscrape\Pscrape\Facades\ScrapeFacadeAccessor');
        });
    }

    /**
     * Implementation Bindings.
     */
    private function implementationBindings()
    {
        $this->app->bind(
            'Pscrape\Pscrape\Contracts\ScrapeInterface',
            'Pscrape\Pscrape\Scrape'
        );
    }

    /**
     * Registering Other Custom Service Providers.
     */
    private function serviceProviders()
    {
        //        $this->app->register('Pscrape\...\...');
    }