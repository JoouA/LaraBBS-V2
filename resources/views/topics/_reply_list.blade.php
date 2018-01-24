<div class="reply-list">
    @if(count($replies))
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
                        @can('destroy',$reply)
                        <span class="meta pull-right">
                            <form id="reply-delete-form" action="{{ route('replies.destroy',$reply->id) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                <button type="submit">
                                    <span class="glyphicon glyphicon-trash"></span>
                                </button>
                            </form>
                        </span>
                        @endcan
                    </div>
                    <div class="reply-content">
                        {!! $reply->content !!}
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    @else
        <div class="panel panel-heading">
            暂无评论~
        </div>
    @endif
</div>
{{ $replies->links() }}