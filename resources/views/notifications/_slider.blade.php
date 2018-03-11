<div class="col-md-3 main-col">
    <div class="box">
        <div class="padding-md">
            <div class="list-group text-center">
                <a href="{{ route('messages.index') }}" class="list-group-item big {{ active_class(if_route('messages.index') || if_route('messages.show')) }} ">
                    <i class="text-md fa fa-envelope" aria-hidden="true"></i>
                    &nbsp;私信
                </a>

                <a href="{{ route('notifications.index') }}" class="list-group-item big no-pjax  {{ active_class(if_route('notifications.index')) }} ">
                    <i class="text-md fa fa-bell" aria-hidden="true"></i>
                    &nbsp;通知
                </a>
            </div>
        </div>
    </div>
</div>