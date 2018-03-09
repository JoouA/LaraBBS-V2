<?php

namespace App\Http\Controllers\Api;

use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $notifications = $this->user()->notifications()->paginate(20);

        return $this->response->paginator($notifications,new NotificationTransformer());
    }

    public  function stats()
    {
        // 是user表中的数据
        return $this->response->array([
            'unread_count' => $this->user()->notification_count,
        ]);
    }
}
