<?php

namespace App\Http\Controllers;

use App\Poll;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PollsController extends Controller
{
    const HELP = 0;
    const OPEN = 1;
    const VOTE = 2;
    const CLOSE = 3;

    protected $token;
    protected $room;
    protected $rawMsg;
    protected $sender;
    protected $message;
    protected $pollOpenRegex = '/\@poll/g';
    protected $pollCloseRegex = '/\@endpoll/g';
    protected $pollVoteRegex = '/(\+\d|\-\d)/';
    protected $helpCmdRegex = '/help/';
    protected $removeRegex = '/(\[.+\])/g';
    protected $messages = [];
    /**
     * PollsController constructor.
     */
    public function __construct()
    {
        $this->token = env('CW_TOKEN');
    }

    public function webHook(Request $request)
    {
        $this->room = $request->input('webhook_event.room_id');
        $this->rawMsg = $request->input('webhook_event.body');
        $this->sender = $request->input('webhook_event.from_account_id');
        $this->message = $request->input('webhook_event.message_id');

        switch ($this->pollType($this->rawMsg)) {
            case self::HELP:
                break;
            case self::OPEN:
                break;
            case self::VOTE:
                break;
            case self::CLOSE:
                break;
            default: break;
        }
        return response()->json(['status' => 'OK']);
    }

    protected function sendMessage($room, $content = "") {
        return Curl::to("https://api.chatwork.com/v2/rooms/{$room}/messages")
            ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
            ->withData( array( 'body' => $content))
            ->post();
    }

    protected function pollDetect ($msg, $regex) {
        return preg_match($regex, $msg);
    }

    protected function pollType($msg) {
        if ($this->pollDetect($msg, $this->helpCmdRegex)) return self::HELP;
        if ($this->pollDetect($msg, $this->pollOpenRegex)) return self::OPEN;
        if ($this->pollDetect($msg, $this->pollVoteRegex)) return self::VOTE;
        if ($this->pollDetect($msg, $this->pollCloseRegex)) return self::CLOSE;
        return -1;
    }

    protected function stringRemoveRegex($msg) {
        return preg_replace($this->removeRegex, "", $msg);
    }

    protected function openPollAction() {
        $poll = Poll::whereStatus(0)->first();
        if($poll) {
            array_push($this->messages, 'Đang có poll được mở rồi nhé người anh em thiện lành.');
        } else {
            $poll = Poll::create([
                'content' => $this->stringRemoveRegex($this->rawMsg),
                'creator' => $this->sender,
                'message' => $this->message
            ]);
            array_push($this->messages, 'TO ALL >>>');
            array_push($this->messages, __('Mọi người vào vote POLL của [Reply aid=:aid] nhé.', ['aid' => $this->sender]));
            array_push($this->messages, "[info][title][picon:$this->sender}][/title]{$poll->content}[/info]");
            array_push($this->messages, 'Vote bằng cách TO mình hoặc reply tin nhắn này.');
            array_push($this->messages, 'Cảm ơn mọi người nhiều.');
            $this->sendMessage($this->room, $this->newPollMessage());
        }
    }

    protected function newPollMessage () {
        return implode('\n', $this->messages);
    }

}
