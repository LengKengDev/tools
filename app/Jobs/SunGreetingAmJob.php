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
            ->withData( array( 'body' => $this->quoteTemplate($now, $quote )))
            ->post();
        echo $this->quoteTemplate($now, $quote ).PHP_EOL;
    }

    public function quoteTemplate(Carbon $now, Quote $quote)
    {

        return "Chào buổi sáng cả nhà ạ, hôm nay là ngày {$now->day}/{$now->month}.".PHP_EOL
            ."Chúc mọi người một ngày làm việc hiệu quả và đầy niềm vui nhé!".PHP_EOL
            ."Thông tin thời tiết ngày hôm nay:".PHP_EOL
            ."[info][title]Quote trong ngày[/title]{$quote->content}[/info]";
    }
}
