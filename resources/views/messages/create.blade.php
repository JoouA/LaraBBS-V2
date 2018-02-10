@extends('layouts.app')

@section('title','新私信')

@section('content')
    <div class="container">
        @include('notifications._slider')
        <div class="col-md-9">
            <div class="panel panel-default">

                <div class="panel-body">
                    <h3 class="text-center">
                        <span class="fa fa-envelope " aria-hidden="true"></span>发私信
                    </h3>
                    <hr>
                    <div>
                        <a href="{{ route('users.show',$recipient->id) }}" title="{{ $recipient->name }}">
                            <img class="img-circle img-thumbnail " style="width: 48px;height: 48px" alt="{{ $recipient->name }}" src="{{ $recipient->avatar }}">
                            {{ $recipient->name }}
                        </a>
                    </div>
                    <br>
                    <form class="form-horizontal" method="POST" action="{{ route('messages.store') }}" accept-charset="UTF-8">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input name="recipient_id" type="hidden" value="{{ $recipient->id }}">
                        <div class="form-group">
                            <div class="col-sm-8">
                                <textarea class="form-control" rows="5" name="message" cols="50" id="reply_content" required="" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 164px;"></textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary loading-on-clicked"><i class="fa fa-paper-plane" aria-hidden="true"></i> 发送</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
