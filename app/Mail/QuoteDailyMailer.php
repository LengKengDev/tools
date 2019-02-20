<?php

namespace App\Mail;

use App\Quote;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class QuoteDailyMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $days;

    const GREETING = [
        'AM' => [
            'Chào buổi sáng :em',
            'Hi :em, good morning <3',
            'Hello :em, good morning with <3',
            'おはようございます。:em <3'
        ],
        'PM' => [
            'Chúc :em ngủ ngon zZz',
            'Chúc :em ngủ ngon nhé',
            'G9 :em !!!',
            'Goodnight :em <3',
            'おやすみなさい　:em'
        ]
    ];

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mode = 'AM')
    {
        $start = Carbon::parse("2017-02-07 01:00:00");
        $now = Carbon::now();

        $this->days = $start->diffInDays($now);

        $quote = Quote::find($this->days % Quote::count() + 1);
        $this->content = __($quote->content, ['em' => Quote::EM[rand(0, count(Quote::EM) - 1)]]);

        $this->subject = __("{$now->day}/{$now->month} <3 ".self::GREETING[$mode][$now->day % count(self::GREETING[$mode]) + 1],
            ['em' => Quote::EM[rand(0, count(Quote::EM) - 1)]]);
        echo $this->subject."\n";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('ohmygodvt95@gmail.com')
            ->subject($this->subject)
            ->view('emails.quotes.plain');
    }
}
