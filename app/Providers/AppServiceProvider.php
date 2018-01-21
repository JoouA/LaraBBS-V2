<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\Reply;
use App\Observers\LinkObserver;
use App\Observers\ReplyObserver;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use App\Observers\TopicObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 视图共享变量
        $categories = Category::all();
        view()->share('categories', $categories);

        Topic::observe(TopicObserver::class);

        Reply::observe(ReplyObserver::class);

        Link::observe(LinkObserver::class);

        Carbon::setLocale('zh');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        if (app()->isLocal()) {
            $this->app->register(\VIACreative\SudoSu\ServiceProvider::class);
        }
    }
}
