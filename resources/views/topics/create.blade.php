@extends('layouts.app')

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
                        <select class="form-control" name="category_id" required>
                            @if(!old('category_id'))
                                <option value="" hidden disabled selected>请选择分类</option>
                            @endif
                            @foreach ($categories as $value)
                                <option value="{{ $value->id }}" {{ old('category_id')== $value->id? 'selected':'' }}>{{ $value->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea name="body" class="form-control" id="editor" rows="3" placeholder="请填入至少三个字符的内容。" required>{{ old('body') }}</textarea>
                    </div>

                    <div class="well well-sm">
                        <button type="submit" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> 保存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection