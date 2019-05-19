<?php

namespace App\Console\Commands;

use App\Jobs\SunGreetingAmJob;
use App\Jobs\SunGreetingPmJob;
use Illuminate\Console\Command;

class SunGreeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sun:greeting {mode=AM}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->isTodayWeekend()) {
            if ($this->argument('mode') == 'AM') {
                dispatch(new SunGreetingAmJob());
            }
            else {
                dispatch(new SunGreetingPmJob());
            }
        }
    }

    function isTodayWeekend() {
        return in_array(date("l"), ["Saturday", "Sunday"]);
    }
}
