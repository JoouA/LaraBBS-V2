<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
     /*   'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],*/
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\ClearLastSession'
        ],
        'Illuminate\Auth\Events\Authenticated' => [
            'App\Listeners\StoreLastSession'
        ],
        // 当用户创建Topic的时候，发消息给那些关注他的人
        'App\Events\CreateTopic' => [
            'App\Listeners\SendCreateTopicNotificationToFollowers',
        ],

        // 当用户用户回复话题的时候，通知关注他的人
        'App\Events\CreateReply' => [
            'App\Listeners\SendCreateReplyNotificationToFollowers',
        ],

        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\Weixin\WeixinExtendSocialite@handle'
        ],
        /*'eloquent.created: Illuminate\Notifications\DatabaseNotification' => [
            'App\Listeners\PushNotification',
        ],*/

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
