@extends('layouts.app')

@section('title',$topic->title)

@section('description', $topic->excerpt)

@section('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/simditor.css') }}">
@stop

@section('content')

<div class="row">
    <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="text-center">
                    作者：{{ $topic->user->name }}
                </div>
                <hr>
                <div class="media">
                    <div align="center">
                        <a href="{{ route('users.show', $topic->user->id) }}">
                            <img class="thumbnail img-responsive" src="{{ $topic->user->avatar }}" width="300px" height="300px">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 topic-content">
        <div class="panel panel-default">
            <div class="panel-body">
                <h1 class="text-center">
                    {{ $topic->title }}
                </h1>

                <div class="article-meta text-center">
                    {{ $topic->created_at->diffForHumans() }}
                    ⋅
                    <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                    {{ $topic->reply_count }}
                </div>

                <div class="topic-body">
                    {!! $topic->body !!}
                </div>

                <div class="operate">
                    <hr>
                    @can('edit',$topic)
                        <a href="{{ route('topics.edit', $topic->id) }}" class="btn btn-default btn-xs pull-left" role="button">
                            <i class="glyphicon glyphicon-edit"></i> 编辑
                        </a>
                    @endcan
                    @can('destroy',$topic)
                        <form action="{{ route('topics.destroy', $topic->id) }}" method="post">
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn btn-default btn-xs pull-left" style="margin-left: 6px">
                                <i class="glyphicon glyphicon-trash"></i>
                                删除
                            </button>
                        </form>
                    @endcan
                </div>

            </div>
        </div>
        <!--  zan list  -->
         @include('topics._zan_box')
        <!-- share -->
        <div class="panel panel-default">
            <div class="social-share"></div>
        </div>
        <!--用户回复列表-->
        <div class="panel panel-default topic-reply">
            <div class="panel-body">
                @includeWhen(Auth::check(),'topics._reply_box',['topic' => $topic])
                @include('topics._reply_list',['replies' => $replies])
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
    <script src="{{ asset('js/emoji-images.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/module.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/hotkeys.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/uploader.js') }}"></script>
    <script type="text/javascript"  src="{{ asset('js/simditor.js') }}"></script>
    <!-- Simditor -->
   {{-- <script>
        $(document).ready(function(){
            var editor = new Simditor({
                textarea: $('#biaoqing'),
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
    </script>--}}

    {{--<script>
        $('.biaoqing').mouseout(function (event) {
            var target = $(event.target);

            var content = target.val();

            var emojified = emoji(content,  '{{ config('app.url') }}' + '/assets/emoji/graphics/emojis/', 30);

            target.val(emojified);

            console.log(emojified);
        });
    </script>--}}

@endsection