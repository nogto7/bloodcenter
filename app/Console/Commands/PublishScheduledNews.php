<?php

namespace App\Console\Commands;

use App\Models\News;
use Illuminate\Console\Command;

class PublishScheduledNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // News::where('is_active', 0)
        //     ->where('publish_at', '<=', now())
        //     ->update(['is_active' => 1]);
    }
}
