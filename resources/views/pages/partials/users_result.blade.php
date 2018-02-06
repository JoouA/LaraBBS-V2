<div class="result user media">
    <div class="media">
        <div class="avatar media-left">
            <div class="image">
                <a title="{{ $user_result->name }}" href="{{ route('users.show', $user_result->id) }}">
                    <img class="media-object img-thumbnail img-circle " style="width: 85px;height: 85px" src="{{ $user_result->avatar }}" >
                </a>
            </div>
        </div>
        <div class="media-body user-info">
            <div class="info">
                <a href="{{ route('users.show', $user_result->id) }}">
                    {{ $user_result->name }}
                </a>
                @if ($user_result->hasRole('Founder'))
                    <div class="role-label">
                        <span class="label label-success">Founder</span>
                    </div>
                @endif

                @if ($user_result->introduction)
                    | {{ $user_result->introduction }}
                @endif

            </div>
            <div class="info number">
                第 {{ $user_result->id }} 位会员
                ⋅
                <span title="注册日期">
                    {{ $user_result->created_at->diffForHumans() }}
                </span>

                {{--⋅ <span>{{ $user_result->follower_count }}</span> 关注者--}}
                ⋅ <span>{{ $user_result->topics_count }}</span> 篇话题
                ⋅ <span>{{ $user_result->replies_count }}</span> 条回帖
                {{--⋅ <span>{{ $user_result->article_count }}</span> 篇文章--}}
            </div>
        </div>
    </div>
</div>
<hr>
