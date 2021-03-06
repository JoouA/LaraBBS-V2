<div class="panel panel-default">
    <div class="panel-body">
        <a href="{{ route('topics.create') }}" class="btn btn-block btn-success" aria-hidden="true" aria-label="Left Align">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>新建话题
        </a>
    </div>
</div>
@if (count($active_users))
    <div class="panel panel-default">
        <div class="panel-body active-users">

            <div class="text-center">活跃用户</div>
            <hr>
            @foreach ($active_users as $active_user)
                <a class="media" href="{{ route('users.show', $active_user->id) }}">
                    <div class="media-left media-middle">
                        <img src="{{ $active_user->avatar }}" width="24px" height="24px" class="img-circle media-object">
                    </div>

                    <div class="media-body">
                        <span class="media-heading">{{ $active_user->name }}</span>
                    </div>
                </a>
            @endforeach

        </div>
    </div>
@endif

@if(count($links))
    <div class="panel panel-default">
        <div class="panel-body active-users">

            <div class="text-center">资源推荐</div>
            @foreach($links as $link)
                <hr>
                <a href="{{ $link->link }}" class="media" target="_blank">
                    <div class="media-left media-middle">
                        <img src="{{ $link->avatar }}" width="24px" height="24px" alt="{{$link->title}}" class="media-object">
                    </div>
                    <div class="media-body">
                        <span class="media-heading">{{ $link->title }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif