<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Export extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export';

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
        $output = fopen(database_path('textures/export/export.csv'), 'w');
        fputcsv($output, [
            '企業名',
            '総調達額',
            '最終資金調達',
            '設立年月日',
            '業種',
            '住所',
            '電話番号',
            'WEBサイト',
            'タイプ',
            '都道府県',
            '事業内容',
            '従業員数',
            '社長',
        ]);
        for($i = 0; $i <= 72; $i++) {
            $quoteList = json_decode(file_get_contents(database_path('textures/data/'.$i.'.json')), true);


            foreach ($quoteList['data'] as $item) {
                fputcsv($output, [
                    $item['name'],
                    $item['totalFunding'],
                    $item['lastFundraised'],
                    $item['established'],
                    $item['industry'],
                    $item['address'],
                    $item['telephone'],
                    $item['website'],
                    $item['type'],
                    $item['state'],
                    $item['description'],
                    $item['employees'],
                    $item['representativeName'],
                ]);
            }

        }
        fclose($output);
        echo database_path('textures/export/export.csv').PHP_EOL;
    }
}
