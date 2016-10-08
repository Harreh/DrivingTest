<?php

require 'vendor/autoload.php';

use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Mink, Behat\Mink\Session, Behat\Mink\Driver\GoutteDriver, Behat\Mink\Driver\Goutte\Client as GoutteClient;

$options = getopt('l:r:');

if (!isset($options['l']) || !isset($options['r'])) {
    throw new InvalidArgumentException('Missing required command-line arguments.');
}

$licence = $options['l'];
$reference = $options['r'];

$mink = new Mink(array(
    'default' => new Session(new GoutteDriver(new GoutteClient()))
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

echo $mink->getSession()->getPage()->getContent();

