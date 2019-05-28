<?php

namespace App\Console\Commands;

use App\Watermark;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RemoveFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'watermark:clean';

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
        $now = Carbon::now()->subDay();
        $files = Watermark::where('created_at', '<=', $now)->get();
        foreach ($files as $file) {
            File::delete(public_path($file->name));
            echo public_path($file->name).PHP_EOL;
            $file->delete();
        }
    }
}
