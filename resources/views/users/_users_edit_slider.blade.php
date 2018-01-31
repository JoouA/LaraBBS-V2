<div class="box">
    <div class="padding-md ">
        <div class="list-group text-center">
            <a href="{{ route('users.edit',Auth::id()) }}" class="list-group-item {{ active_class(if_route('users.edit')) }}">
                <i class="text-md fa fa-list-alt" aria-hidden="true"></i>
                &nbsp;个人信息
            </a>
            <a href="{{ route('users.edit_avatar',Auth::id()) }}" class="list-group-item {{ active_class(if_route('users.edit_avatar')) }}">
                <i class="text-md fa fa-picture-o" aria-hidden="true"></i>
                &nbsp;修改头像
            </a>
            <a href="{{ route('users.edit_password',Auth::id()) }}" class="list-group-item  {{ active_class(if_route('users.edit_password')) }}">
                <i class="text-md fa fa-lock" aria-hidden="true"></i>
                &nbsp;修改密码
            </a>
        </div>
    </div>
</div>