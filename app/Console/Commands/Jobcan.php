<?php

namespace App\Console\Commands;

use App\Jobs\TouchJob;
use App\Slack;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class Jobcan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jobcan {type=checkin}';

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
        if (!$this->isHoliday()) {
            $slacks = Slack::with('user')
                ->where($this->argument('type'), 1)->get();

            foreach ( $slacks as $slack) {
                $time = $this->argument('type') == 'checkin'
                    ? now()->addMinutes(mt_rand(15, 25))
                    : now()->addMinutes(mt_rand(1, 30));
                dispatch(new TouchJob($slack, $this->argument('type')))->delay($time);
                activity()->performedOn($slack)
                    ->causedBy($slack->user)
                    ->log($this->argument('type').' スケジュール済み　'.$time->toTimeString());
            }
        } else {
            $this->comment('Today is holiday');
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function isHoliday() {
        $holiday = json_decode(file_get_contents(database_path('textures/holiday.json')), true);
        $now = Carbon::now()->toDateString();
        return in_array($now, array_keys($holiday));
    }
}
