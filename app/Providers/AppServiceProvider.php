<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Link;
use App\Models\Topic;
use App\Models\Reply;
use App\Models\User;
use App\Observers\LinkObserver;
use App\Observers\ReplyObserver;
use App\Observers\UserObserver;
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
        \View::composer('layouts._header',function ($view){
            $categories = Category::all();
            $view->with('categories', $categories);
        });

        Topic::observe(TopicObserver::class);

        Reply::observe(ReplyObserver::class);

        Link::observe(LinkObserver::class);

        User::observe(UserObserver::class);

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
