<?php

namespace App\Console\Commands;

use App\Mail\QuoteDailyMailer;
use Illuminate\Console\Command;
use Mail;

class SendGreetingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'greeting:send {mode=AM}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send drip e-mails to a user';

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
        Mail::to(env('MAIL_TO', 'ohmygodvt95@gmail.com'))
            ->send(new QuoteDailyMailer($this->argument('mode')));
    }
}
