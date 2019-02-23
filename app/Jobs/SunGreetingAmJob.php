<?php

namespace App\Jobs;

use App\Quote;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ixudra\Curl\Facades\Curl;

class SunGreetingAmJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $token = 'f9467b3462da1a2c04a2a8ad42a24b2d';
    protected $room_id = '45126716';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $quote = Quote::whereType(2)->inRandomOrder()->first();

        $response = Curl::to("https://api.chatwork.com/v2/rooms/{$this->room_id}/messages")
            ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
            ->withData( array( 'body' => $this->quoteTemplate($quote )))
            ->post();
        echo $response.PHP_EOL;
    }

    public function quoteTemplate(Quote $quote)
    {
        return "[info][title]Quote trong ngày[/title]{$quote->content}[/info]";
    }
}
