<?php

namespace App\Console\Commands;

use App\DrivingCommandHandler;
use Illuminate\Console\Command;

class Driving extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'driving';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the DrivingTest application process.';

    /**
     * @var DrivingCommandHandler
     */
    protected $handler;

    /**
     * Driving constructor.
     *
     * @param DrivingCommandHandler $handler
     */
    public function __construct(DrivingCommandHandler $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }

    /**
     * @return DrivingCommandHandler
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param DrivingCommandHandler $handler
     *
     * @return Driving
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $handler = $this->getHandler();
        $date = $handler->getDateTime();

        if ($handler->storeBooking($date)) {
            $this->line('There is an earlier booking available on ' . $date->format('l j F Y g:ia'));
        } else {
            $this->line('No earlier bookings availalbe.');
        }
    }
}
