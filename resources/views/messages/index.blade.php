@extends('layouts.app')

@section('title','我的私信列表')

@section('content')
    <div class="container">
        @include('notifications._slider')
        <div class="col-md-9">
            <div class="panel panel-default">

                <div class="panel-body">
                    <h3 class="text-center">
                        <span class="fa fa-envelope" aria-hidden="true"></span>私信列表
                    </h3>
                    <hr>
                    @if($threads->count() > 0)
                        <div class="panel-body remove-padding-horizontal notification-index">
                            <ul class="list-group row">
                                @foreach($threads as $thread)
                                    <?php $unread_messagesCount = $thread->userUnreadMessagesCount(Auth::id()) ?>
                                    <li class="list-group-item media {{ $unread_messagesCount > 0 ? 'unread' : '' }}" style="margin-top: 0px;">
                                        <?php
                                        $participant = $thread->participant();
                                        ?>
                                        <div class="avatar pull-left">
                                            <a href="{{ route('users.show', [$participant->id]) }}">
                                                <img class="media-object img-thumbnail avatar" alt="{{ $participant->name }}" src="{{ $participant->avatar }}"  style="width:48px;height:48px;"/>
                                            </a>
                                        </div>

                                        <div class="infos">
                                            <div class="media-heading">
                                                @if ($thread->latestMessage->user_id == Auth::id())
                                                    我发送给
                                                @endif
                                                <a href="{{ route('users.show', [$participant->id]) }}">
                                                    {{ $participant->name }}
                                                </a>
                                                <span class="meta">
                                                ⋅ 'at' ⋅ <span class="timeago">{{ $thread->latestMessage->created_at }}</span>
                                                 </span>：
                                            </div>
                                            <div class="media-body markdown-reply content-body">
                                                {!! $thread->latestMessage->body  !!}
                                            </div>
                                            <div class="message-meta">
                                                <p>
                                                    <a href="{{ route('messages.show', $thread->id) }}" class="normalize-link-color ">
                                                        @if ($unread_messagesCount > 0)
                                                            <span style="color:#ff7b00;">
                                                            <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                                                {{ $unread_messagesCount }} 条未读消息
                                                            </span>
                                                        @else
                                                            <i class="fa fa-commenting-o" aria-hidden="true"></i>
                                                            查看对话
                                                        @endif
                                                    </a>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="panel-footer text-right remove-padding-horizontal pager-footer">
                            {!! $threads->render() !!}
                        </div>
                    @else
                        <div class="panel-body">
                            <div class="empty-block">消息列表为空！</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
