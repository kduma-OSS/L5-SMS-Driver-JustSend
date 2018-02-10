<?php
namespace KDuma\SMS\Drivers\JustSend;

use KDuma\SMS\SMSManager;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class JustSendServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(SMSManager::class)->extend('justsend', function (Application $app, array $config) {
            return new JustSendDriver(
                $config['key'] ?? '',
                $config
            );
        });
    }
}