@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">用户登录</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group {{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">用户名</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">密码</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('captcha') ? 'has-error' : '' }}">
                            <label for="captcha" class="col-md-4 control-label">验证码</label>

                            <div class="col-md-3">
                                <input type="text" class="form-control" name="captcha" id="captcha">

                                @if($errors->has('captcha'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('captcha') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <img src="{{ captcha_src('default') }}" alt="captcha" onclick="this.src='captcha/default?'+ Math.random()">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> 记住我
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary btn-block">
                                    登录
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    忘记密码?
                                </a>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 14px;">
                            <div class="col-md-6 col-md-offset-4">
                                <a href="{{ route('github.provider') }}">
                                    <i class="fa fa-github"></i> 使用Github登录
                                </a>
                                <a href="#" class="pull-right">
                                    <i class="fa fa-weibo"></i> 使用微博登录
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
