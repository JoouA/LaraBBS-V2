@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-3">
        @include('users._users_edit_slider');
    </div>

    <div class="panel panel-default col-md-9">
        <div class="panel-heading">
            <h2><i class="fa fa-cog" aria-hidden="true"></i> 编辑个人资料</h2>
        </div>
        @include('common.error')
        <div class="panel-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="name-field">用户名</label>
                    <input class="form-control" type="text" name="name" id="name-field" value="{{ old('name', $user->name ) }}" />
                </div>

                <div class="form-group">
                    <label for="email-field">邮 箱</label>
                    <input class="form-control" type="text" name="email" id="email-field" value="{{ old('email', $user->email ) }}" />
                </div>

                <div class="form-group">
                    <label for="introduction-field">个人简介</label>
                    <textarea name="introduction" id="introduction-field" class="form-control" rows="3">{{ old('introduction', $user->introduction ) }}</textarea>
                </div>

                <div class="form-group">
                    <label for="avatar">头像</label>
                    <input type="file" name="avatar">
                    @if($user->avatar)
                        <br>
                        <img src="{{ $user->avatar }}" class="thumbnail img-thumbnail" alt="avatar" width="200px"/>
                    @endif
                </div>

                <div class="well well-sm">
                    <button type="submit" class="btn btn-primary btn-block">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection