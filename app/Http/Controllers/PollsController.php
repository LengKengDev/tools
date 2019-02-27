<?php

namespace App\Http\Controllers;

use App\Poll;
use App\Quote;
use App\Response;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class PollsController extends Controller
{
    const ALL= -1;
    const HELP = 0;
    const OPEN = 1;
    const VOTE = 2;
    const CLOSE = 3;
    const INFO = 4;
    const QUOTE = 5;
    const FORCE_CLOSE = 6;

    protected $token;
    protected $room;
    protected $rawMsg;
    protected $sender;
    protected $message;
    protected $pollOpenRegex = '/\#poll/i';
    protected $pollCloseRegex = '/\#endpoll/i';
    protected $forceCloseRegex = '/\#forceendpoll/i';
    protected $pollVoteRegex = '/(\+\d|\-\d)/i';
    protected $pollVoteRegexPlus = '/\+\d/i';
    protected $pollVoteRegexMinus = '/\-\d/i';
    protected $helpCmdRegex = '/#help/i';
    protected $infoCmdRegex = '/#info/i';
    protected $quoteCmdRegex = '/#quote/i';
    protected $allCmdRegex = '/\[toall\]/i';
    protected $removeRegex = '/(\[.+\])/i';
    protected $messages = [];
    protected $response;
    /**
     * PollsController constructor.
     */
    public function __construct()
    {
        $this->token = env('CW_TOKEN');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function webHook(Request $request)
    {
        $this->room = $request->input('webhook_event.room_id');
        $this->rawMsg = $request->input('webhook_event.body');
        $this->sender = $request->input('webhook_event.from_account_id');
        $this->message = $request->input('webhook_event.message_id');

        switch ($type = $this->pollType($this->rawMsg)) {
            case self::HELP:
                $this->response = $this->helpAction();
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
            case self::FORCE_CLOSE:
                $this->response = $this->forceCloseAction();
                break;
            case self::INFO:
                $this->response = $this->infoAction();
                break;
            case self::QUOTE:
                $this->response = $this->quoteAction();
                break;
            case self::ALL:
                break;
            default: break;
        }
        return response()->json(['type' => $type, 'status' => $this->response]);
    }

    /**
     * @param $room
     * @param string $content
     * @return mixed
     */
    protected function sendMessage($room, $content = "") {
        return Curl::to("https://api.chatwork.com/v2/rooms/{$room}/messages")
            ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
            ->withData( array( 'body' => $content))
            ->post();
    }

    /**
     * @param $msg
     * @param $regex
     * @return false|int
     */
    protected function pollDetect ($msg, $regex) {
        return preg_match($regex, $msg);
    }

    /**
     * @param $msg
     * @return int
     */
    protected function pollType($msg) {
        $msg = $this->stringRemoveRegex($msg);
        if ($this->pollDetect($this->rawMsg, $this->allCmdRegex)) return self::ALL;
        if ($this->pollDetect($msg, $this->helpCmdRegex)) return self::HELP;
        if ($this->pollDetect($msg, $this->pollOpenRegex)) return self::OPEN;
        if ($this->pollDetect($msg, $this->pollVoteRegex)) return self::VOTE;
        if ($this->pollDetect($msg, $this->pollCloseRegex)) return self::CLOSE;
        if ($this->pollDetect($msg, $this->forceCloseRegex)) return self::FORCE_CLOSE;
        if ($this->pollDetect($msg, $this->infoCmdRegex)) return self::INFO;
        if ($this->pollDetect($msg, $this->quoteCmdRegex)) return self::QUOTE;
        return -1;
    }

    /**
     * @param $msg
     * @return string|string[]|null
     */
    protected function stringRemoveRegex($msg) {
        return preg_replace($this->removeRegex, "", $msg);
    }

    /**
     * @return string
     */
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
            array_push($this->messages, "[info][title][picon:{$this->sender}] say:[/title]{$poll->content}[/info]");
            array_push($this->messages, 'Vote bằng cách TO em hoặc REPLY tin nhắn cho em với cú pháp +1 or -1 nhé.');
            array_push($this->messages, 'Cảm ơn mọi người nhiều lắm');
        }
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    protected function newPollMessage () {
        return implode(PHP_EOL, $this->messages);
    }

    /**
     * @return int|string
     */
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
        }
        else if ($this->pollDetect($this->stringRemoveRegex($this->rawMsg), $this->pollVoteRegexMinus)){
            $reply->update(['action' => -1]);
            array_push($this->messages, ';(');
        } else {
            return 1;
        }
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    /**
     * @return string
     */
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
        array_push($this->messages, __('Poll của [picon::sender] tạo từ :time đã kết thúc, số người vote +1 là: :total !',
            ['sender' => $poll->creator, 'time' => $poll->created_at->diffForHumans(), 'total' => count($responses)]));
        if(strlen($str) > 0) {
            array_push($this->messages, 'Danh sách người VOTE:');
            array_push($this->messages, '[info]'.$str.'[/info]');
        }
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    /**
     * @return string
     */
    private function helpAction()
    {
        array_push($this->messages, 'Em sẽ hỗ trợ mọi người tạo poll trên chatwork nhanh chóng với chỉ vài giây ạ.');
        array_push($this->messages, '■　Dưới đây là các lệnh để sử dụng:');
        array_push($this->messages, '[info]#help - Xem hướng dẫn sử dụng');
        array_push($this->messages, '#poll - Mở một poll mới, bất cứ tin nhắn nào có nó đều sẽ được em chuyển thành poll ợ. Cú pháp: "#poll nội dung rất dài đằng sau.." ');
        array_push($this->messages, '#endpoll - Kết thúc một poll đang open, CHỈ có người mở poll mới có hiệu quả.');
        array_push($this->messages, '#info - Xem kết quả realtime của poll đang mở hoặc poll gần nhất trong room. Ai cũng xem được.');
        array_push($this->messages, '#forceendpoll - End poll trong trường hợp người tạo mất tích, các admin room sẽ có quyền dùng lệnh này.');
        array_push($this->messages, '#quote - Lấy một quote ngẫu nhiên thú vị :D.[/info]');
        array_push($this->messages, '■　Lưu ý:');
        array_push($this->messages, '・Chỉ có một poll được mở trong một phiên. Nghĩa là phải end thì mới open mới đc');
        array_push($this->messages, '・Mọi lệnh đều sử dụng bằng cách To hoặc Re với em ạ.');
        $quote = Quote::whereType(2)->inRandomOrder()->first();
        array_push($this->messages, '■　Nhân tiện tặng mọi người 1 câu quote ạ　(h) :');
        array_push($this->messages, '[info]'.$quote->content.'[/info]');

        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    /**
     * @return string
     */
    private function infoAction()
    {
        $poll = Poll::whereRoom($this->room)->orderBy('id', 'DESC')->first();
        if ($poll == null) {
            array_push($this->messages, 'Từ lúc e mặc cái áo mới chưa có cái poll nào ợ.');
        } else {
            $responses = Response::where('poll_id', $poll->id)->where('action', 1)->get();
            $str = "";
            foreach ($responses as $res) {
                $str .= __('[picon::id]', ['id' => $res->sender]);
            }
            array_push($this->messages, __('Poll được tạo bởi [picon::sender] từ :time, số người :status vote +1 là: :total !',
                ['sender' => $poll->creator, 'time' => $poll->created_at->diffForHumans(), 'status' => $poll->status == 0 ? 'đang' : 'đã','total' => count($responses)]));
            if(strlen($str) > 0) {
                array_push($this->messages, 'Danh sách người VOTE:');
                array_push($this->messages, '[info]'.$str.'[/info]');
            }
        }

        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    /**
     * @return string
     */
    private function quoteAction()
    {
        $quote = Quote::whereType(2)->inRandomOrder()->first();
        array_push($this->messages, '(mailaanhem)');
        array_push($this->messages, '[info][title]Quote ngẫu nhiên:[/title]'.$quote->content.'[/info]');

        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }

    /**
     * @return string
     */
    private function forceCloseAction()
    {
        $poll = Poll::whereStatus(0)->whereRoom($this->room)->first();

        if ($poll == null) {
            array_push($this->messages, 'Từ lúc e mặc cái áo mới chưa có cái poll nào ợ.');
        } else {
            if ($this->sender == $poll->creator) {
                return $this->closeAction();
            }
            $res = Curl::to("https://api.chatwork.com/v2/rooms/{$this->room}/members")
                ->withHeaders( array( "X-ChatWorkToken: {$this->token}") )
                ->get();
            $res = json_decode($res, true);
            foreach ($res as $elm) {
                if ($elm['account_id'] == $this->sender && $elm['role'] == 'admin') {
                    return $this->closeAction();
                }
            }
        }
        array_push($this->messages, 'Em không có quyền close poll này, bảo admin close ấy.');
        $this->response = $this->newPollMessage();
        $this->sendMessage($this->room, $this->response);
        return $this->response;
    }
}
