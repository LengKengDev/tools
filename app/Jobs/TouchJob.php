<?php

namespace App\Jobs;

use App\Slack;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Ixudra\Curl\Facades\Curl;

class TouchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $slack;
    protected $token;
    protected $room_id;
    protected $channel;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Slack $slack, $type = 'checkin')
    {
        $this->slack = $slack;
        $this->token = env('CW_TOKEN');
        $this->room_id = env('CW_ROOM');
        $this->channel = env('CHANNEL');
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type) {
            case 'checkin': $this->checkin(); break;
            default: $this->checkout();
        }
    }

    /**
     * @param string $command
     */
    protected function slackCommandExecute($command = '/jobcan_touch', $text = 'Powered by Jobcan Autobot')
    {
        $response = Curl::to('https://slack.com/api/chat.command')
            ->withHeaders([

            ])
            ->withData([
                'token' => $this->slack->token,
                'channel' => $this->slack->channel ?? $this->channel,
                'command' => $command,
                'text' => "{$this->slack->user->name} - ".$text
            ])
            ->post();
        $status = json_decode($response, true);
        return $status['ok'] ?? false;
    }

    /**
     * @param string $text
     * @return mixed
     */
    protected function notify($text = 'Tự động checkin jobcan :D')
    {
        $to = "@{$this->slack->user->name} ";
        if ($this->slack->cw) {
            $to = "[To:{$this->slack->cw}] ";
        }
        $response = Curl::to("https://api.chatwork.com/v2/rooms/{$this->room_id}/messages")
            ->withHeaders(["X-ChatWorkToken: {$this->token}"])
            ->withData( ['body' => $to.$text])
            ->post();
        activity()->performedOn($this->slack)
            ->causedBy($this->slack->user)
            ->log($to.$text);
        return json_decode($response, true);
    }

    /**
     * checkin
     */
    protected function checkin() {
        if ($this->slackCommandExecute('/jobcan_touch')) {
            $this->notify("打刻しました。（入室）");
        }
        else {
            $this->notify("エラーが発生しました。まだ打刻しません。");
        }
    }

    /**
     * checkout
     */
    protected function checkout() {
        if($this->slackCommandExecute('/jobcan_touch')) {
            $this->notify("打刻しました。（退室）");
        } else {
            $this->notify("エラーが発生しました。まだ打刻しました。");
        }
    }
}
