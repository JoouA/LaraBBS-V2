<div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="media">
                <h3 class="media-heading text-center">
                    {{ $user->name }}
                </h3>
                <div align="center">
                    <img src="{{ $user->avatar }}"
                         class=" img-thumbnail img-circle" alt="avatar" width="200px" height="200px">
                </div>
                <div class="media-body">
                    <hr>
                    <h4><strong>个人简介</strong></h4>
                    <p>{{ $user->introduction }}</p>
                    <hr>
                    <div class="follow-info row">
                        <div class="col-xs-4">
                            <a class="counter"  id="followers" href="{{ route('users.followers',$user->id) }}">{{ $user->followers->count() }}</a>
                            <a class="text" href="{{ route('users.followers',$user->id) }}">关注者</a>
                        </div>
                        <div class="col-xs-4">
                            <a class="counter" href="{{ route('users.replies',$user->id) }}">{{ $user->replies->count() }}</a>
                            <a class="text" href="{{ route('users.replies',$user->id) }}">讨论</a>
                        </div>
                        <div class="col-xs-4">
                            <a class="counter" href="{{ route('users.topics',$user->id) }}">{{ $user->topics->count() }}</a>
                            <a class="text" href="{{ route('users.topics',$user->id) }}">文章</a>
                        </div>
                    </div>
                    <hr>
                    <h4><strong>注册于</strong></h4>
                    <p>{{ $user->created_at->diffForHumans() }}</p>
                    <hr>
                    <h4><strong>活跃于</strong></h4>
                    <p title="{{ $user->last_actived_at }}">{{ $user->last_actived_at->diffForHumans() }}</p>
                    @if(Auth::check())
                        @if($user->id !== Auth::id())
                            <hr>
                            <a  class="btn btn-block {{ Auth::user()->isFollowing($user)? 'btn-default' : 'btn-danger' }} "  href="javascript:void(0);"
                               data-url="{{ route('users.follow',$user->id) }}" data-follow="{{  Auth::user()->isFollowing($user)? 'T' : 'F' }}"  id="user-follow-button" style="cursor:pointer;">
                                <i class="fa fa-minus"></i>{{ Auth::user()->isFollowing($user)? ' 已关注' : ' 关注TA' }}
                            </a>
                            <a class="btn btn-default btn-block" href="{{ route('messages.create',$user->id) }}">
                                <i class="fa fa-envelope-o"></i> 发私信
                            </a>
                        @endif
                    @endif
                </div>
                @can('update',$user)
                    <hr>
                    <a class="btn btn-primary btn-block" href="{{ route('users.edit',$user->id) }}" id="user-edit-button">
                        <i class="fa fa-edit"></i> 编辑个人资料
                    </a>
                @endcan
            </div>
        </div>
    </div>
    <div class="box text-center">
        <div class="padding-sm user-basic-nav">
            <ul class="list-group">
                <a href="{{ route('users.followings',$user->id) }}" class="">
                    <li class="list-group-item"><i class="text-md fa fa-eye"></i> Ta 关注的用户</li>
                </a>
                <a href="{{ route('users.votes',$user->id) }}" class="">
                    <li class="list-group-item"><i class="text-md fa fa-thumbs-up"></i> Ta 赞过的话题</li>
                </a>
            </ul>
        </div>
    </div>
</div>