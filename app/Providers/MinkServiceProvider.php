<?php

namespace App\Providers;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Illuminate\Support\ServiceProvider;
use Behat\Mink\Driver\Goutte\Client as GoutteClient;

class MinkServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(DriverInterface::class, function ($app) {
            return new GoutteDriver(new GoutteClient());
        });

        $this->app->bind(Mink::class, function ($app) {
            $mink = new Mink([
                'default' => new Session($app[DriverInterface::class])
            ]);

            $mink->setDefaultSessionName('default');

            return $mink;
        });
    }

}
