<?php

namespace CampaigningBureau\WhatsAToolClient;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class WhatsAToolClientProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // publish config-files
        $this->publishes([
            __DIR__ . '/../config/whatsatool.php' => config_path('whatsatool.php'),
        ]);

        // add custom msdisdn-validation
        Validator::extend('msisdn', 'MsisdnValidator@validate');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
