@extends('layouts.app')

@section('title','我的通知')

@section('content')
<div class="container">
    @include('notifications._slider')
    <div class="col-md-9">
        <div class="panel panel-default">

            <div class="panel-body">
                <h3 class="text-center">
                    <span class="glyphicon glyphicon-bell" aria-hidden="true"></span>我的通知
                </h3>
                <hr>

                @if(count($notifications) >0 )
                    @foreach($notifications as $notification)
                        {{--@include('notifications.types._'.snake_case(class_basename($notification->type)))--}}
                        @include('notifications.types._'.'reply_notification')
                    @endforeach
                    {!! $notifications->render() !!}
                @else
                    <div class="empty-block">
                        没有消息通知!
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
