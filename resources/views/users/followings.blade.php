@extends('layouts.app')

@section('title', $user->name . ' 的关注用户' )

@section('content')
    <div class="row">
        @include('users._users_slider')
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <ol class="breadcrumb panel panel-heading">
                <li><a href="{{ route('users.show',$user->id) }}">个人中心</a></li>
                <li class="active">Ta 关注的用户</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-body">
                    @if(count($followings))
                        <ul class="list-group">
                            @foreach($followings as $following)
                                <li class="list-group-item">
                                    <a href="{{ route('users.show',$following->id) }}" title="{{ $following->name }}">
                                        <!-- <img class="avatar-topnav" alt="zhuhai" src=""> -->
                                        <img class="img-thumbnail img-circle inline-block " style="width: 38px;height: 38px;" src="{{ $following->avatar }}">
                                        {{ $following->name }}
                                        <span class="meta">
                                        <span> ⋅ </span>{{ $following->followers_count }} 关注者
                                        <span> ⋅ </span>{{ $following->replies_count }}  回复
                                        <span> ⋅ </span>{{ $following->topics_count }} 文章
                                    </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="pull-right">
                            {!! $followings->links() !!}
                        </div>
                    @else
                        暂无关注的用户(〃'▽'〃)
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection