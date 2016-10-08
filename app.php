<?php

require 'vendor/autoload.php';

use Behat\Mink\Mink, Behat\Mink\Session, Behat\Mink\Driver\GoutteDriver, Behat\Mink\Driver\Goutte\Client as GoutteClient;
use \GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

$options = getopt('l:r:', ['mock']);

if (!isset($options['l']) || !isset($options['r'])) {
    throw new InvalidArgumentException('Missing required command-line arguments.');
}

$licence = $options['l'];
$reference = $options['r'];

$goutteClient = new GoutteClient();

if (isset($options['mock'])) {
    $mock = new MockHandler([
        new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], file_get_contents('pages/page1.html')),
        new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], file_get_contents('pages/page2.html')),
        new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], file_get_contents('pages/page3.html')),
        new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], file_get_contents('pages/page4.html')),
        new Response(200, ['Content-Type' => 'text/html; charset=utf-8'], file_get_contents('pages/page5.html'))
    ]);

    $handler = HandlerStack::create($mock);

    $goutteClient->setClient(new GuzzleClient([
        'handler' => $handler
    ]));
}

$mink = new Mink(array(
    'default' => new Session(new GoutteDriver($goutteClient))
));

$mink->setDefaultSessionName('default');

$mink->getSession()->visit('https://www.gov.uk/change-driving-test');

$mink->getSession()->getPage()->find('css', '#get-started > a')->click();

$mink->getSession()->getPage()->fillField('driving-licence-number', $licence);
$mink->getSession()->getPage()->fillField('application-reference-number', $reference);
$mink->getSession()->getPage()->pressButton('booking-login');

$mink->getSession()->getPage()->clickLink('date-time-change');
$mink->getSession()->getPage()->fillField('test-choice-earliest', 'ASAP');
$mink->getSession()->getPage()->pressButton('driving-licence-submit');

$dateText = $mink->getSession()->getPage()->find('css', '#availability-results ul > li a span')->getText();

$date = DateTime::createFromFormat('l j F Y g:ia', $dateText);

echo $date->format('l j F Y g:ia');
