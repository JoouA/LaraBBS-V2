@extends('layouts.app')

@section('title', $user->name . ' 的关注者' )

@section('content')
<div class="row">
    @include('users._users_slider')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <ol class="breadcrumb panel panel-heading">
            <li><a href="{{ route('users.show',$user->id) }}">个人中心</a></li>
            <li class="active">Ta 的关注者</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                @if(count($followers))
                    <ul class="list-group">
                        @foreach($followers as $follower)
                            <li class="list-group-item">
                                <a href="{{ route('users.show',$follower->id) }}" title="{{ $follower->name }}">
                                    <!-- <img class="avatar-topnav" alt="zhuhai" src=""> -->
                                    <img class="img-thumbnail img-circle inline-block " style="width: 38px;height: 38px;" src="{{ $follower->avatar }}">
                                    {{ $follower->name }}
                                    <span class="meta">
                                        <span> ⋅ </span>{{ $follower->followers_count }} 关注者
                                        <span> ⋅ </span>{{ $follower->replies_count }}  回复
                                        <span> ⋅ </span>{{ $follower->topics_count }} 文章
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="pull-right">
                        {!! $followers->links() !!}
                    </div>
                @else
                    暂无关注者(〃'▽'〃)
                @endif
            </div>
        </div>
    </div>
</div>
@endsection