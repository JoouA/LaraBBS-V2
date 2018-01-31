@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-3 main-col">
        @include('users._users_edit_slider');
    </div>
    <div class="panel panel-default col-md-9">
        <div class="panel-heading">
            <h2><i class="fa fa-picture-o" aria-hidden="true"></i> 请选择图片</h2>
        </div>
        @include('common.error')
        <div class="panel-body padding-bg">
            <form method="POST" action="https://laravel-china.org/users/19867/update_avatar" enctype="multipart/form-data" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="PUT">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div id="image-preview-div">
                    <label for="exampleInputFile">请选择图片：</label>
                    <br>
                    <img id="preview-img" class="avatar-preview-img" src="{{ $user->avatar }}">
                </div>
                <div class="form-group">
                    <input type="file" name="avatar" id="file" required="">
                </div>
                <br>
                <button class="btn btn-lg btn-primary" id="upload-button" type="submit">上传头像</button>
            </form>
        </div>
    </div>
</div>
@endsection