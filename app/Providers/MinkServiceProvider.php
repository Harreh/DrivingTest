<?php

namespace App\Providers;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Behat\Mink\Driver\Goutte\Client as GoutteClient;
use GuzzleHttp\Client as GuzzleClient;

class MinkServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(GoutteClient::class, function ($app) {
            $client = new GoutteClient();

            if (Config::get('driving.mock')) {
                $mock = new MockHandler([
                    new Response(200, ['Content-Type' => 'text/html; charset=utf-8'],
                        file_get_contents('pages/page1.html')),
                    new Response(200, ['Content-Type' => 'text/html; charset=utf-8'],
                        file_get_contents('pages/page2.html')),
                    new Response(200, ['Content-Type' => 'text/html; charset=utf-8'],
                        file_get_contents('pages/page3.html')),
                    new Response(200, ['Content-Type' => 'text/html; charset=utf-8'],
                        file_get_contents('pages/page4.html')),
                    new Response(200, ['Content-Type' => 'text/html; charset=utf-8'],
                        file_get_contents('pages/page5.html'))
                ]);

                $handler = HandlerStack::create($mock);

                $client->setClient(new GuzzleClient([
                    'handler' => $handler
                ]));
            }

            return $client;
        });

        $this->app->bind(DriverInterface::class, function ($app) {
            return new GoutteDriver($app[GoutteClient::class]);
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
