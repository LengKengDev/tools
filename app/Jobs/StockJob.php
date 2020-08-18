<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ixudra\Curl\Facades\Curl;

class StockJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $token;
    protected $room_id;
    protected $data;
    protected $code = '4053.T';
    protected $apiKey = null;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token = env('CW_TOKEN');
        $this->room_id = env('CW_ROOM');
        $this->code = env('STOCK_CODE', '4053.T');
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
            CURLOPT_URL => "https://yahoo-finance-free.p.rapidapi.com/v6/finance/quote?region=US&lang=en&symbols={$this->code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: yahoo-finance-free.p.rapidapi.com",
                "x-rapidapi-key: {$this->apiKey}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $obj = json_decode($response);
            $this->data = $obj->quoteResponse->result[0];
            $response = Curl::to("https://api.chatwork.com/v2/rooms/{$this->room_id}/messages")
                ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
                ->withData( array( 'body' => $this->template()))
                ->post();
        }

    }

    protected function template() {
        $time = Carbon::createFromTimestamp($this->data->regularMarketTime)->toDayDateTimeString();
        return "[info][title]Sun*Stock {$time}[/title]"
            ."Giá hiện tại: {$this->data->regularMarketPrice} 円".PHP_EOL
            ."Khoảng giá trong ngày: {$this->data->regularMarketDayRange} 円".PHP_EOL
            ."Chart: https://www.tradingview.com/chart/?symbol=TSE%3A4053"
            ."[/info]";
    }
}