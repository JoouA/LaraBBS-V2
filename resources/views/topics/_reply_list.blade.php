<div class="reply-list">
    @foreach($replies as $reply)
        <div class="media" name="reply{{ $reply->id }}" id="reply{{ $reply->id }}">
            <div class="avatar pull-left">
                <a href="">
                    <img  class="media-object img-thumbnail" src="{{ $reply->user->avatar }}"
                         style="width: 48px;height: 48px" alt="{{ $reply->user->name }}">
                </a>
            </div>

            <div class="infos">
                <div class="media-heading">
                    <a href="{{ route('users.show',$reply->user->id) }}" title="{{ $reply->user->name }}">
                        {{ $reply->user->name }}
                    </a>
                    <span> •  </span>
                    <span class="meta" title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>

                    {{--删除回复--}}
                    <span class="meta pull-right">
                        <a title="删除回复">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </a>
                    </span>
                </div>
                <div class="reply-content">
                    {!! $reply->content !!}
                </div>
            </div>
        </div>
        <hr>
    @endforeach
</div>