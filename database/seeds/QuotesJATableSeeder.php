<?php

use App\Quote;
use Illuminate\Database\Seeder;

class QuotesJATableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $quoteList = json_decode(file_get_contents(database_path('textures/quote.ja.json')), true);
        foreach ($quoteList as $quote) {
            Quote::create($quote);
        }
    }
}
