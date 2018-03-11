<div class="media">
    <div class="avatar pull-left">
        @if(class_basename($notification->type) == 'FollowNotification')
            <a href="{{ route('users.show',$notification->data['follower_id']) }}" title="{{ $notification->data['follower_name'] }}">
                <img src="{{ $notification->data['follower_avatar'] }}" class="media-object img-thumbnail img-circle"
                     style="width: 48px;height: 48px" alt="{{ $notification->data['follower_name'] }}">
            </a>
        @else
            <a href="{{ route('users.show',$notification->data['user_id']) }}" title="{{ $notification->data['user_name'] }}">
                <img src="{{ $notification->data['user_avatar'] }}" class="media-object img-thumbnail img-circle"
                     style="width: 48px;height: 48px" alt="{{ $notification->data['user_name'] }}">
            </a>
        @endif
    </div>

    <div class="infos">
        @if(class_basename($notification->type) == 'ReplyNotification')
            <div class="media-heading">
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                评论了文章
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
                    <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
            <div class="media-body">
                {!! $notification->data['reply_content'] !!}
            </div>
        @endif
        @if(class_basename($notification->type) == 'ReplyMentionNotification')
            <div class="media-heading">
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                在评论
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                中回复了你
                <span class="meta pull-right" title="{{ $notification->created_at }}">
            <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
            </span>
            </div>
            <div class="media-body">
                {!! $notification->data['reply_content'] !!}
            </div>
        @endif
        @if(class_basename($notification->type) == 'ReplyToFollowersNotification')
            <div class="media-heading">
                您关注的用户
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                评论了文章
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
            <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
            </span>
            </div>
            <div class="media-body">
                {!! $notification->data['reply_content'] !!}
            </div>
        @endif
        @if(class_basename($notification->type) == 'VoteNotification')
            <div class="media-heading">
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                点赞了您的文章
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
                <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
            </span>
            </div>
            <div class="media-body">
                {{--{!! $notification->data['topic_content'] !!}--}}
            </div>
        @endif
        @if(class_basename($notification->type) == 'VoteToFollowersNotification')
            <div class="media-heading">
                您关注的用户
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                点赞了文章
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
            <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
        </span>
            </div>
            <div class="media-body">
                {{--{!! $notification->data['topic_content'] !!}--}}
            </div>
        @endif
        @if(class_basename($notification->type) == 'FollowersNotification')
            <div class="media-heading">
                您关注的用户
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                发布了新文章
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
                <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
            <div class="media-body">
                {{--{!! $notification->data['topic_content'] !!}--}}
            </div>
        @endif
        @if(class_basename($notification->type) == 'TopicToFollowersNotification')
            <div class="media-heading">
                您关注的用户
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                发布了新文章
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
            <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
            </span>
            </div>
            <div class="media-body">
                {{--{!! $notification->data['topic_content'] !!}--}}
            </div>
        @endif
        {{-- 关注消息提醒 --}}
        @if(class_basename($notification->type) == 'FollowNotification')
            <div class="media-heading">
                用户
                <a href="{{ route('users.show',$notification->data['follower_id']) }}">
                    {{ $notification->data['follower_name'] }}
                </a>
                关注了你您
                <span class="meta pull-right" title="{{ $notification->created_at }}">
                <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
        @endif

        {{--给关注的人发送我关注他人的消息--}}
        @if(class_basename($notification->type) == 'FollowToFollowersNotification')
            <div class="media-heading">
                您关注的用户
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                关注了
                <a href="{{ route('users.show',$notification->data['following_id']) }}">
                    {{ $notification->data['following_name'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
            <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
            </span>
            </div>
        @endif
    </div>
</div>
<hr>