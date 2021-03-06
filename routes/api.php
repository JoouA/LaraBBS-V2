<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array','bindings','change-locale']
],function ($api){

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ],function ($api){
        //图片验证码
        $api->post('captchas','CaptchasController@store')->name('api.captchas.store');

        // 短信验证码
        $api->post('verificationCodes','VerificationCodesController@store')->name('api.verificationCodes.store');

        // 用户注册
        $api->post('users','UsersController@store')->name('api.users.store');


        // 第三方登录
        $api->post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')->name('api.socials.authorizations.store');

        // 登录
        $api->post('authorizations','AuthorizationsController@store')->name('api.authorizations.store');

        // 刷新token
        $api->put('authorizations/current','AuthorizationsController@update')->name('api.authorizations.update');

        // 删除token
        $api->delete('authorizations/current','AuthorizationsController@destroy')->name('api.authorizations.destroy');

    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ],function ($api){
        // 游客可以访问的接口
        $api->get('categories','CategoriesController@index')->name('api.categories.index');

        // 获取topic列表
        $api->get('topics','TopicsController@index')->name('api.topics.index');

        // 获取topic详情
        $api->get('topics/{topic}','TopicsController@show')->name('api.topics.show');

        //获取某人发布的topics
        $api->get('users/{user}/topics','TopicsController@userIndex')->name('api.users.topics.index');

        // 评论列表
        $api->get('topics/{topic}/replies','RepliesController@index')->name('api.topics.replies.index');

        //某个用户评论列表
        $api->get('users/{user}/replies','RepliesController@userIndex')->name('api.users.replies.index');

        // 推荐资源接口
        $api->get('links','LinksController@index')->name('api.links.index');

        // 活跃用户接口
        $api->get('users/actived','UsersController@activeUsers')->name('api.users.actived');

        // 关注用户接口
        $api->get('users/{user}/followers','UsersController@followers')->name('api.user.followers');

        // 被关注用户的接口
        $api->get('users/{user}/followings','UsersController@followings')->name('api.user.followings');

        //需要token的接口
        $api->group([ 'middleware' => 'api.auth' ],function ($api){
            //当前用户的登录信息
            $api->get('user','UsersController@me')->name('api.user.show');

            //编辑登陆用户的信息
            $api->patch('user','UsersController@update')->name('api.user.update');

            //图片资源
            $api->post('images','ImagesController@store')->name('api.images.store');

            //发布话题
            $api->post('topics','TopicsController@store')->name('api.topics.store');

            // 更新话题
            $api->patch('topics/{topic}','TopicsController@update')->name('api.topics.update');

            // 删除话题
            $api->delete('topics/{topic}','TopicsController@destroy')->name('api.topics.destroy');

            //发布评论
            $api->post('topics/{topic}/replies','RepliesController@store')->name('api.topics.replies.store');

            // 更新评论
            $api->patch('replies/{reply}','RepliesController@update')->name('api.replies.update');

            // 删除评论
            $api->delete('replies/{reply}','RepliesController@destroy')->name('api.replies.destroy');

            // 通知列表
            $api->get('user/notifications','NotificationsController@index')->name('api.user.notifications.index');

            // 通知统计
            $api->get('user/notifications/stats','NotificationsController@stats')->name('api.user.notification.stats');

            // 标记消息为已读
            $api->patch('user/read/notifications','NotificationsController@read')->name('api.user.notifications.read');

            // 当前登录用户的权限
            $api->get('user/permissions','PermissionsController@index')->name('api.user.permissions.index');

            // 对topic进行点赞
            $api->post('topics/{topic}/vote','TopicsController@vote')->name('api.topics.topic.vote');

        });
    });
});
