<?php

namespace App\Console\Commands;

use App\Jobs\TranslateSlug;
use App\Models\Topic;
use Illuminate\Console\Command;

class CalculateTopicSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larabbs:calculate-topic-slug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '生成专题的slug';

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
        $topics = Topic::all();

        foreach ($topics as $topic){
            if (empty($topic->slug)){
                dispatch(new TranslateSlug($topic));
            }
            $this->info($topic->id . '、slug生成成功');
        }

    }
}
