@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/simditor-html.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/simditor-markdown.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/Simditor-PrettyEmoji/styles/simditor-prettyemoji.css') }}">
@stop

@section('content')
<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">

            <div class="panel-body">
                <h2 class="text-center">
                    <i class="glyphicon glyphicon-edit"></i>
                        新建话题
                </h2>
                <hr>
                @include('common.error')
                <form action="{{ route('topics.store') }}" method="POST" accept-charset="UTF-8">

                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">
                        <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="请填写标题" required/>
                    </div>

                    <div class="form-group">
                        <select class="form-control basic-single" name="category_id" required>
                            <option value="" hidden disabled selected>请选择分类</option>
                            @foreach ($categories as $value)
                                <option value="{{ $value->id }}" {{ $value->id == old('category_id')? 'selected' : '' }} >{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea name="body" class="form-control" id="editor" rows="3" placeholder="请填入至少三个字符的内容。" required> {!! old('body')  !!}</textarea>
                    </div>

                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/simditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/beautify-html.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/simditor-html.js') }}"></script>
    <script type="text/javascript" src="{{ asset('vendor/Simditor-PrettyEmoji/lib/simditor-prettyemoji.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/simditor-autosave.js') }}"></script>

    <!-- simditor-markdown -->
    <script type="text/javascript" src="{{ asset('assets/js/marked.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/to-markdown.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/simditor-markdown.js') }}"></script>



    <!-- Simditor -->
    <script>
        $(document).ready(function(){
            var editor = new Simditor({
                textarea: $('#editor'),
                autosave: 'editor-content',
                toolbar: ['bold', 'italic', 'color' , 'fontScale' ,'underline', 'strikethrough', '|', 'ol', 'ul', 'blockquote', 'code', '|', 'link', 'image', 'emoji','|', 'html','markdown' ,'indent', 'outdent'],
                emoji: {
                    autoClose: true,
                    imagePath: '/vendor/Simditor-PrettyEmoji/images/emoji/',
                    categories: ["face","fashion","animal","food","travel","time","work","font","tool","other"]
                },
                upload: {
                    url : '{{ route('topics.upload_image') }}',
                    params: { _token: '{{ csrf_token() }}'},
                    fileKey: 'upload_file',
                    connectionCount: 3,
                    leaveConfirm: '文件上传中，关闭此页面将取消上传。',
                },
                pasteImage: true,
            });
        });
    </script>
    <!-- select2 -->
    <script>
        $(document).ready(function () {
            $('.basic-single').select2();
        });
    </script>
@stop