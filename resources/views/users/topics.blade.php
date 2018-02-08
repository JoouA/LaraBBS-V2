@extends('layouts.app')

@section('title',$user->name . ' 的专题界面')

@section('content')
<div class="row">
    @include('users._users_slider')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <ol class="breadcrumb panel panel-heading">
            <li><a href="{{ route('users.show',$user->id) }}">个人中心</a></li>
            <li class="active">Ta 发布的话题({{ $topics->total() }})</li>
        </ol>
        <div class="panel panel-default">
            <div class="panel-body">
                @if(count($topics))
                    <ul class="list-group">
                        @foreach($topics as $topic)
                            <li class="list-group-item">
                                <a href="{{ $topic->link() }}" title="{{ $topic->title }}" class="title">
                                    {{ $topic->title }}
                                </a>
                                <span class="meta">
                                    <a href="{{ route('categories.show',$topic->category->id) }}" title="{{ $topic->category->name }}">{{ $topic->category->name }}</a>
                                    <span> ⋅ </span>{{ $topic->votes_count }} 点赞
                                    <span> ⋅ </span>{{ $topic->replies_count }} 回复
                                    <span> ⋅ </span><span class="timeago">{{ $topic->updated_at->diffForHumans() }}</span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="pull-right">
                        {!! $topics->links() !!}
                    </div>
                @else
                    暂无数据(〃'▽'〃)
                @endif
            </div>
        </div>
    </div>
</div>
@endsection