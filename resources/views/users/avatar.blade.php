@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/slim.min.css') }}">
@endsection

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
            <label for="exampleInputFile">请选择图片：</label>
            <br>
            <div style="width: 400px;height: 400px">
                <div class="slim"
                     data-service="{{ route('users.update_avatar', ['_token' => csrf_token(), 'user_id' => Auth::id()]) }}"
                     data-label="点击选择图片"
                     data-download="true"
                     data-button-edit-title="编辑"
                     data-button-download-title="下载"
                     data-button-upload-title="上传"
                     data-button-remove-title="删除"
                     data-button-cancel-title="取消"
                     data-button-confirm-title="确认"
                     data-button-cancel-label="取消"
                     data-button-confirm-label="确认"
                     data-size="960,960"
                     data-ratio="1:1">
                    <img src="{{ $user->avatar }}" alt=""/>
                    <input type="file" name="slim[]" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/slim.kickstart.min.js') }}"></script>
@endsection