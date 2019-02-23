<?php

use App\Quote;
use Illuminate\Database\Seeder;

class QuotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $i = 1;

    public function run()
    {
        Quote::create([
            'content' => 'Đêm đã qua nhường cho ngày tươi sáng
Hãy mở lòng đón ánh nắng ban mai
Đón yêu thương và nụ cười mãi mãi
Chào buổi sáng chúc :em một ngày vui',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Chúc :em ngày mới tốt lành!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Trong những điều hạnh phúc nhất của cuộc đời, với anh đấy chính là lúc em nhận lời yêu anh. Anh thật sự hạnh phúc khi mỗi sáng lại được nhắn tin cho người mình yêu. Chúc :em một ngày tràn ngập niềm vui',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ':em dậy đã thức giấc chưa? Anh nhớ em. Buổi sáng tốt lành em nhé!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Em hãy tin rằng, khi anh nhắn tin vào buổi sáng chỉ có thể là lời chúc ngọt ngào: Ngày mới rạng rỡ và niềm tin em nhé!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Anh chúc :em một ngày mới tuyệt vời. Luôn mỉm cười em nhé!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Mỗi buổi sáng mang lại niềm hi vọng và những giấc mơ mới. Chúc :em ngày mới thành công! Chào buổi sáng!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Hôm nay, khi anh thức giấc anh tưởng rằng em đang ở bên cạnh anh. Và anh đã nhận ra em có ý nghĩa với anh như thế nào. Chúc em buổi sáng tốt vui vẻ, em yêu!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Sẽ có những ngày tốt và xấu, Nhưng anh muốn :em biết rằng dù thế nào anh vẫn ở đây đợi em. Chào buổi sáng, :em yêu',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Buổi sáng mới nhiều hi vọng và hoài bão mới :em nhé!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Hãy để màn đêm xóa tan những lo lắng ưu phiền, để khi em thức dậy vào ngày mai với một khởi đầu mới.',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Anh muốn gửi lời chúc buổi sáng an lành này tới em! Dậy đi :em nhé! :v',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ':v Lời chào buổi sáng không chỉ có nghĩa đơn giản là chúc buổi sáng tốt lành, mà nó còn có ý nghĩa em là người đầu tiên anh nghĩ đến khi anh thức giấc.',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Thất bại của ngày hôm qua không thể là nguyên nhân làm mất đi phước lành của ngày hôm nay. Mỗi ngày đều hứa hẹn niềm vui, niềm hạnh phúc và cả sự tha thứ. Chào buổi sáng :em.',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Chúc :em buổi sáng tốt lành. Mọi việc đều như ý muốn!. :v',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Mặt trời đã mọc, khởi đầu cho một ngày mới rồi. Dậy đi :em và hãy tận hưởng ngày hôm nay! Chào buối sáng.',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ':v Những điều tuyệt vời nhất trong cuộc sống bắt đầu từ những việc nhỏ nhặt. Anh :em chúc  một ngày mới tốt đẹp!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Chúc :em một buối sáng tốt lành, tình yêu của anh!',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => '<3 Chúc :em buổi sáng ngủ dậy có nụ cười thật tươi, có một ngày mới thật tuyệt vời em nhé.',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => '(y) Chúc :em buổi sáng tốt lành. Chúc bạn có một ngày tràn ngập những khoảnh khắc hào hứng và tuyệt vời.',
            'type' => 0,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ':em đã nhận được tin nhắn của anh chưa nhỉ? Nếu chưa nhận được thì em hãy tiếp tục đọc tin nhắn này để biết được thông điệp của anh trong ngày hôm nay nhé: Chúc em ngày mới rạng ngời hạnh phúc và mãi bên anh! Yêu em ngốc ạ',
            'type' => 0,
            'day' => $this->i++
        ]);

        $this->i = 1;

        Quote::create([
            'content' => '<3 Chúc :em ngủ ngon trong muôn ngàn nỗi nhớ!. Để ngày mới sang vẫn ấm một tấm lòng!. <3',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Đêm nay anh không biết nhắn gì cho :em cả ! Anh chỉ biết bây giờ anh đang nhớ em, nhớ rất nhiều, tuy nơi đây trời mưa và rất lạnh nhưng anh chỉ mong em có giấc ngủ thật ngon ấm áp trong những giấc mơ hạnh phúc :em nhé!',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Sông có thể cạn núi có thể mòn nhưng với anh việc chúc em ngủ ngon sẽ không bao giờ thay đổi. Chúc :em ngủ thật ngon em iu nhé! <3',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ':em yêu ah, đến giờ đi ngủ rồi đó. Nhớ là đừng có thức khuya xem nhé phải giữ gìn sức khỏe chứ! Ngoan anh mới yêu.hj. Chúc :em ngoan của anh ngủ thật ngon ợ! <3',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Sau một ngày mệt mỏi, hứa với anh là em sẽ quên mọi muộn phiền. Để chỉ giữ lại những gì ngọt ngào vào giấc ngủ thôi nhé. Chúc :em ngủ ngon ợ! <3',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ' <3 Cho dù bầu trời màu xanh hay màu xám, cho dù bầu trời có trăng hay có sao, miễn là trái tim em chân thành, những giấc mơ ngọt ngào sẽ luôn ở bên em. Chúc :em ngủ ngon!!',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ' <3 Anh nhắn tin cho :em …chỉ mún chúc em ngủ ngon thôi…  Hãy ngủ thật ngon vào em nhé…. Chúc :em ngủ ngon ợ!!',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ' Thôi ngủ đi ! anh chúc :em ngủ ngon , tối gặp ác mộng, hihi! trong ác mộng em là một ác nhân ! đang hành hạ một tội nhân là anh đây! ahihi',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Em là lí do mà có đêm anh ko ngủ được. Chúc :em ngủ ngon ợ! T.T G9',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Một giấc mơ đẹp hôm nay khi em ngủ, và một nụ cười cho buổi sớm mai thức dậy. Chúc cho tất cả giấc mơ và điều em mong ước có thể thành hiện thực…. Chúc :em ngủ ngon',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => ' Chúc :em đêm nay gặp thật nhiều ác mộng …. Và anh sẽ hiện ra để cùng em chạy trốn :v',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Ngủ ngon nhé :em …có 1 người luôn quan tâm và nhớ về em. G9 :(',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Chúc ngủ ngon, nhiều ác mộng, trong ác mộng ta sẽ lại gặp nhau và cùng chạy trốn. Yêu :em',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Chúc người ju bé nhỏ của anh ngủ thật ngoan cho ….. cả thế giới đc yên. Yêu :em :v',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Thuê bao quý khách đang dùng hiện đang bị 1 thuê bao khác để ý. Đề nghị quý khách chú ý: Đắp chăn và ngủ thôi! G9 :em',
            'type' => 1,
            'day' => $this->i++
        ]);
        Quote::create([
            'content' => 'Nằm xuống giường, nhắm mắt vào, đi ngủ đi và đừng có mơ về anh iu đấy nhé. G9 :em',
            'type' => 1,
            'day' => $this->i++
        ]);

        $quoteList = json_decode(file_get_contents(database_path('textures/quote.json')), true);
        foreach ($quoteList as $quote) {
            Quote::create($quote);
        }
    }
}
