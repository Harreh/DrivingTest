<?php

namespace App;

use App\Models\Booking;
use Behat\Mink\Mink;

class DrivingCommandHandler
{

    /**
     * @var Mink
     */
    protected $mink;

    /**
     * @var array
     */
    protected $config;

    /**
     * DrivingCommandHandler constructor.
     *
     * @param Mink $mink
     */
    public function __construct(Mink $mink)
    {
        $this->mink = $mink;
    }

    /**
     * @return Mink
     */
    public function getMink()
    {
        return $this->mink;
    }

    /**
     * @param Mink $mink
     *
     * @return DrivingCommandHandler
     */
    public function setMink(Mink $mink)
    {
        $this->mink = $mink;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     * @throws \Exception
     */
    public function getConfig($key)
    {
        if (!isset($this->config[$key])) {
            throw new \Exception("Driving config item '$key' not found.");
        }

        return $this->config[$key];
    }

    /**
     * @param array $config
     *
     * @return DrivingCommandHandler $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        $session = $this->getMink()->getSession();

        $session->visit('https://www.gov.uk/change-driving-test');

        $session->getPage()->find('css', '#get-started > a')->click();

        $session->getPage()->fillField('driving-licence-number', $this->getConfig('licence'));
        $session->getPage()->fillField('application-reference-number', $this->getConfig('reference'));
        $session->getPage()->pressButton('booking-login');

        $session->getPage()->clickLink('date-time-change');
        $session->getPage()->fillField('test-choice-earliest', 'ASAP');
        $session->getPage()->pressButton('driving-licence-submit');

        $dateText = $session->getPage()->find('css', '#availability-results ul > li a span')->getText();

        return \DateTime::createFromFormat('l j F Y g:ia', $dateText);
    }

    public function storeBooking(\DateTime $date)
    {
        $booking = Booking::where('date', '<=', $date)->first();

        if (isset($booking)) {
            return false;
        }

        $booking = new Booking();
        $booking->date = $date;

        return $booking->save();
    }

}
