<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ixudra\Curl\Facades\Curl;

class NewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $token;
    protected $room_id;
    protected $apiKey = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($room_id = null)
    {
        $this->token = env('CW_TOKEN');
        if ($room_id == null) {
            $this->room_id = env('CW_ROOM');
        } else {
            $this->room_id = $room_id;
        }
        $this->apiKey = env('STOCK_APIKEY', '');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://google-news.p.rapidapi.com/v1/top_headlines?lang=vn&country=VN",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: google-news.p.rapidapi.com",
                "x-rapidapi-key: {$this->apiKey}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (!$err) {
            $obj = json_decode($response);
            $response = Curl::to("https://api.chatwork.com/v2/rooms/{$this->room_id}/messages")
                ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
                ->withData( array( 'body' => $this->template($obj->articles)))
                ->post();
        }
    }

    private function template($data) {
        return "[info][title]Tin tức mới top trong ngày (lightbulb) [/title]"
            .$data[0]->title.PHP_EOL.'➝ '.$data[0]->link.PHP_EOL
            .$data[1]->title.PHP_EOL.'➝ '.$data[1]->link.PHP_EOL
            .$data[2]->title.PHP_EOL.'➝ '.$data[2]->link.PHP_EOL
            .$data[3]->title.PHP_EOL.'➝ '.$data[3]->link.PHP_EOL
            .$data[4]->title.PHP_EOL.'➝ '.$data[4]->link.PHP_EOL
            ."[/info]";
    }
}
