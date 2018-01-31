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
                    <h4><strong>注册于</strong></h4>
                    <p>{{ $user->created_at->diffForHumans() }}</p>
                    <hr>
                    <h4><strong>活跃于</strong></h4>
                    <p title="{{ $user->last_actived_at }}">{{ $user->last_actived_at->diffForHumans() }}</p>
                </div>
                <hr>
                @can('update',$user)
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
                <a href="https://laravel-china.org/users/19867/following" class="">
                    <li class="list-group-item"><i class="text-md fa fa-eye"></i> Ta 关注的用户</li>
                </a>
                <a href="{{ route('users.votes',$user->id) }}" class="">
                    <li class="list-group-item"><i class="text-md fa fa-thumbs-up"></i> Ta 赞过的话题</li>
                </a>
            </ul>
        </div>
    </div>
</div>