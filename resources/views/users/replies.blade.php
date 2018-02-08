@extends('layouts.app')

@section('title',$user->name . ' 的讨论界面')

@section('content')
    <div class="row">
        @include('users._users_slider')
        <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
            <ol class="breadcrumb panel panel-heading">
                <li><a href="{{ route('users.show',$user->id) }}">个人中心</a></li>
                <li class="active">Ta 发表的回复({{ $replies->total() }})</li>
            </ol>
            <div class="panel panel-default">
                <div class="panel-body">
                    @if(count($replies))
                        <ul class="list-group">
                            @foreach($replies as $reply)
                                <li class="list-group-item">
                                    <a href="{{ $reply->topic->link(['#reply'.$reply->id]) }}" title="{{ $reply->topic->title }}" class="remove-padding-left">
                                        {{ $reply->topic->title }}
                                    </a>
                                    <span class="meta">
                                        at <span class="timeago" title="{{ $reply->created_at->diffForHumans() }}">{{ $reply->created_at->diffForHumans() }}</span>
                                    </span>
                                    <div class="reply-body markdown-reply content-body">
                                        {!! $reply->content  !!}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                        <div class="pull-right">
                            {!! $replies->links() !!}
                        </div>
                    @else
                        暂无数据(〃'▽'〃)
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection