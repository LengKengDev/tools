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

    protected $token;
    protected $room_id;
    protected $city;
    protected $weatherKey;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token = env('CW_TOKEN');
        $this->room_id = env('CW_ROOM');
        $this->city = env('CITY_KEY');
        $this->weatherKey = env('WEATHER_KEY');
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
    }

    public function quoteTemplate(Carbon $now, Quote $quote)
    {

        return "(flex) CHÀO BUỔI SÁNG CẢ NHÀ Ạ {$now->day}/{$now->month} (flex).".PHP_EOL.PHP_EOL
            ."(h) Chúc tất cả mọi người một ngày làm việc hiệu quả và đầy niềm vui nhé! (h)".PHP_EOL.PHP_EOL
            ."[info][title]Thông tin thời tiết ngày hôm nay (gogo)[/title]{$this->parseWeather()}[/info]".PHP_EOL
            ."↓[info][title]Tình hình Covid 19 (whew)[/title]Việt Nam: https://ncov.moh.gov.vn/".PHP_EOL
            ."Nhật Bản: https://www.stopcovid19.jp/[/info]"
            ."↓[info][title]Luôn luôn lắng nghe, luôn luôn thấu hiểu (devil)[/title]Trong quá trình làm việc tại Sun*Nhật, hẳn ai cũng có những lúc có những khó khăn, bất mãn, những góp ý, đóng góp mà không biết chia sẻ cùng ai. Vậy thì hãy điền cả vào đây, mọi ý kiến, khó khăn, bất mãn của bạn sẽ đều được lắng nghe và thấu hiểu!
https://docs.google.com/forms/d/e/1FAIpQLSdGnL_3GxTsBtjcHes_MgGPT3IMBnpT-pjEUmM8RmvdgxxYCw/viewform[/info]"
            ."(F)(F)(F)(F)(F)(F)(F)(F)(F)(F)";
    }

    protected function parseWeather () {
        $response = Curl::to("http://dataservice.accuweather.com/forecasts/v1/daily/1day/{$this->city}?apikey={$this->weatherKey}&language=vi&details=true")
            ->get();
        $arr = json_decode($response, true);
        $string = "Nhiệt độ giao động từ {$this->f2c($arr['DailyForecasts'][0]['Temperature']['Minimum']['Value'])} đến {$this->f2c($arr['DailyForecasts'][0]['Temperature']['Maximum']['Value'])} độ C.".PHP_EOL;
        $day = mb_strtolower($arr['DailyForecasts'][0]['Day']['LongPhrase']);
        $night = mb_strtolower($arr['DailyForecasts'][0]['Night']['LongPhrase']);
        $string .= "Ban ngày trời có {$day}, ban đêm thì {$night}.";
        return $string;
    }

    protected function f2c($f) {
        return round(5 / 9 * ($f - 32));
    }
}
