@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-3 main-col">
        @include('users._users_edit_slider')
    </div>
    <div class="panel panel-default col-md-9">
        <div class="panel-heading">
            <h2><i class="fa fa-lock" aria-hidden="true"></i> 修改密码</h2>
        </div>
        <div class="panel-body">
            <form class="form-horizontal" method="POST" action="{{ route('users.update_password',Auth::id()) }}" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label class="col-md-2 control-label">邮 箱：</label>
                    <div class="col-md-6">
                        <input name="" class="form-control" type="text" value="{{ $user->email }}" disabled>
                        <input name="email" type="hidden" value="{{ $user->email }}">
                    </div>
                </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-md-2 control-label">密 码：</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-2 control-label">确认密码：</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <input class="btn btn-primary" id="user-edit-submit" type="submit" value="应用修改">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection