<div class="media">
    <div class="avatar pull-left">
        <a href="{{ route('users.show',$notification->data['user_id']) }}">
            <img src="{{ $notification->data['user_avatar'] }}" class="media-object img-thumbnail img-circle"
                 style="width: 48px;height: 48px" alt="{{ $notification->data['user_name'] }}">
        </a>
    </div>

    <div class="infos">
        @if(class_basename($notification->type) == 'ReplyNotification')
            <div class="media-heading">
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                评论了
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
                    <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
        @endif
        @if(class_basename($notification->type) == 'ReplyToFollowersNotification')
            <div class="media-heading">
                您关注的用户
                <a href="{{ route('users.show',$notification->data['user_id']) }}">
                    {{ $notification->data['user_name'] }}
                </a>
                评论了
                <a href="{{ $notification->data['topic_link'] }}">
                    {{ $notification->data['topic_title'] }}
                </a>
                <span class="meta pull-right" title="{{ $notification->created_at }}">
            <span class="glyphicon  glyphicon-time" aria-hidden="true"></span>
                    {{ $notification->created_at->diffForHumans() }}
            </span>
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
        @endif
    </div>
</div>
<hr>