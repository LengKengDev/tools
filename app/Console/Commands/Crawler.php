<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ixudra\Curl\Facades\Curl;

class Crawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for ($i = 0; $i <= 72; $i++) {
            $response = Curl::to($this->url($i))->withHeaders([
                'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36',
                'x-csrf-token: 9uv6CH84eVfKkbcjo2aagHjhMe3zgh3QxL1aZoRH5m0=',
                'Cookie: _ep_activity=f4f5855b386228d2a0cfc55215e5033e76494a2d46f5b4a528687365f4139677; _ga=GA1.2.202590680.1562663036; _gid=GA1.2.505032705.1562663036; _mkto_trk=id:550-EMV-558&token:_mch-entrepedia.jp-1562663036190-58868; __utma=129562328.202590680.1562663036.1562663160.1562663160.1; __utmc=129562328; __utmz=129562328.1562663160.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none); __utmb=129562328.2.10.1562663160; _session_id=70348f57876879fa5231dd2f9bf0b2bf; _ep_session=70348f57876879fa5231dd2f9bf0b2bf',
            ])->withContentType('application/json')->get();

            file_put_contents(database_path('textures/data/'.$i.'.json'), $response);
            echo database_path('textures/data/'.$i.'.json').PHP_EOL;
        }
    }

    /**
     * @param int $page
     * @return string
     */
    protected function url($page = 0) {
        $offset = $page * 200;
        return "https://entrepedia.jp/api/companies?fields=name%2CtotalFunding%2ClastFundraised%2Cindustry%2Cdescription%2Cstate%2Ctag%2CrepresentativeName%2Cestablished%2Ctype%2Ccreated%2Cupdated%2Cupdated&limit=200&offset={$offset}";
    }
}
