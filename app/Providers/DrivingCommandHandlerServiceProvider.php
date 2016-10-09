<?php

namespace App\Providers;

use App\DrivingCommandHandler;
use Behat\Mink\Mink;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class DrivingCommandHandlerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(DrivingCommandHandler::class, function ($app) {
            $handler = new DrivingCommandHandler($app[Mink::class]);

            $handler->setConfig(Config::get('driving'));

            return $handler;
        });
    }
}
