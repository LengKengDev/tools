<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PollsController extends Controller
{
    protected $token = '209f50fc909d5cca5f8aa1b1fcbc7bd9';
    protected $room_id = '121001809';

    public function webHook(Request $request)
    {
        $response = Curl::to("https://api.chatwork.com/v2/rooms/{$this->room_id}/messages")
            ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
            ->withData( array( 'body' => $this->quoteTemplate(json_encode($request->all()))))
            ->post();
        return true;
    }

    public function quoteTemplate($request)
    {

        return "[code]{$request}[/code]";
    }
}
