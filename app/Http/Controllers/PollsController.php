<?php

namespace App\Http\Controllers;

use App\Poll;
use App\Response;
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
    protected $pollOpenRegex = '/\@poll/';
    protected $pollCloseRegex = '/\@endpoll/';
    protected $pollVoteRegex = '/(\+\d|\-\d)/';
    protected $pollVoteRegexPlus = '/\+\d/';
    protected $pollVoteRegexMinus = '/\-\d/';
    protected $helpCmdRegex = '/help/';
    protected $removeRegex = '/(\[.+\])/';
    protected $messages = [];
    protected $response;
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

        switch ($type = $this->pollType($this->rawMsg)) {
            case self::HELP:
                break;
            case self::OPEN:
                $this->response = $this->openPollAction();
                break;
            case self::VOTE:
                $this->response = $this->voteAction();
                break;
            case self::CLOSE:
                $this->response = $this->closeAction();
                break;
            default: break;
        }
        return response()->json(['type' => $type, 'status' => $this->response]);
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
        $poll = Poll::whereStatus(0)->whereRoom($this->room)->first();

        if($poll) {
            array_push($this->messages, 'Đang có poll được mở rồi nhé người anh em thiện lành.');
        } else {
            $poll = Poll::create([
                'content' => $this->stringRemoveRegex($this->rawMsg),
                'creator' => $this->sender,
                'message' => $this->message,
                'room' => $this->room
            ]);
            array_push($this->messages, 'TO ALL >>>');
            array_push($this->messages, __('Mọi người vào vote POLL của [picon::aid] nhé.', ['aid' => $this->sender]));
            array_push($this->messages, "[info][title][picon:$this->sender}] say:[/title]{$poll->content}[/info]");
            array_push($this->messages, 'Vote bằng cách TO em hoặc reply tin nhắn này với cú pháp +1 or -1 nhé.');
            array_push($this->messages, 'Cảm ơn mọi người nhiều.');
        }
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    protected function newPollMessage () {
        return implode(PHP_EOL, $this->messages);
    }

    protected function voteAction() {
        $poll = Poll::whereStatus(0)->whereRoom($this->room)->firstOrFail();

        $reply = Response::where('sender', $this->sender)
        ->where('poll_id', $poll->id)->first();

        if($reply == null) {
            $reply = Response::create(['sender' => $this->sender, 'poll_id' => $poll->id]);
        }

        if ($this->pollDetect($this->stringRemoveRegex($this->rawMsg), $this->pollVoteRegexPlus)) {
            $reply->update(['action' => 1]);
            array_push($this->messages, '(h)');
        } else {
            $reply->update(['action' => -1]);
            array_push($this->messages, ';(');
        }
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    protected function closeAction() {
        $poll = Poll::whereStatus(0)->whereRoom($this->room)->where('creator', $this->sender)->firstOrFail();

        $responses = Response::where('poll_id', $poll->id)->where('action', 1)->get();
        $poll->update(['status' => 1]);
        $str = "";
        foreach ($responses as $res) {
            $str .= __('[picon::id]', ['id' => $res->sender]);
        }
        array_push($this->messages, 'TO ALL >>>');
        array_push($this->messages, 'Cảm ơn mọi người vì đã tham gia VOTE ạ.');
        array_push($this->messages, __('Poll của [picon::sender] đã kết thúc, số người vote OK là: :total !',
            ['sender' => $poll->creator, 'total' => count($responses)]));
        array_push($this->messages, 'Danh sách người VOTE:');
        array_push($this->messages, '[info]'.$str.'[info]');
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }
}
