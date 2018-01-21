<?php

namespace App\Providers;

use App\Models\Topic;
use App\Models\User;
use App\Policies\ReplyPolicy;
use App\Policies\TopicPolicy;
use App\Policies\UserPolicy;
use Encore\RedisManager\RedisManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        'App\Models\User' => UserPolicy::class,
        'App\Models\Topic' => TopicPolicy::class,
        'App\Models\Reply' => ReplyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        RedisManager::auth(function ($request){
            return \Auth::user()->hasRole('Founder');
        });
    }
}
