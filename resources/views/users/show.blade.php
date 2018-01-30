@extends('layouts.app')

@section('title',$user->name . '的个人中心')

@section('content')
<div class="row">
    @include('users._users_slider')
    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <span>
                    <h1 class="panel-title pull-left" style="font-size:30px;">{{ $user->name }} <small>{{ $user->email }}</small></h1>
                </span>
            </div>
        </div>

        {{--用户发表的内容--}}
        <div class="panel panel-default">
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="{{ active_class(if_query('tab',null)) }}">
                        <a href="{{ route('users.show',$user->id) }}">Ta 的话题</a>
                    </li>
                    <li class="{{ active_class(if_query('tab','replies')) }}">
                        <a href="{{ route('users.show',[$user->id,'tab' => 'replies']) }}">Ta 的回复</a>
                    </li>
                </ul>
                @if(if_query('tab','replies'))
                    @include('users._replies',['replies' => $replies])
                @else
                     @include('users._topics', ['topics' => $topics])
                @endif
            </div>
        </div>
    </div>
</div>
@stop