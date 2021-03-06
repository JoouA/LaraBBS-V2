<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            {{--Collapsed Hamburger--}}
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            {{--Branding Image--}}
            <a href="{{ url('/') }}" class="navbar-brand">
                LaraBBS
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            {{--Left Side of Navbar--}}
            <ul class="nav navbar-nav">
                <li class="{{ active_class(if_route('topics.index')) }}"><a href="{{ route('topics.index') }}">话题</a></li>
                @foreach($categories as $category)
                <li class="{{ active_class(if_route('categories.show')&&if_route_param('category',$category->id)) }}"><a href="{{ route('categories.show',$category->id) }}">{{ $category->name }}</a></li>
                @endforeach
            </ul>

            {{-- Right Side of Navbar--}}
            <ul class="nav navbar-nav navbar-right">
                <!-- 在用户内部搜索 -->
                <!-- $query 参数是搜索的参数，从后台传过来的 -->
                @if (Request::is('users*')&&isset($user) || (Request::is('search*') && $user->id > 0))
                    <form method="GET" action="{{ route('search') }}" accept-charset="UTF-8" class="navbar-form navbar-left hidden-sm hidden-md">
                        <div class="form-group">
                            <input class="form-control search-input mac-style" placeholder="搜索范围：{{ $user->name }}" name="q" type="text" value="{!! (Request::is('search*') && isset($query)) ? $query : ''  !!}">
                            <input class="form-control search-input mac-style"  name="user_id" type="hidden" value="{{ $user->id }}">
                        </div>
                    </form>
                @else
                    <!-- 全站搜索 -->
                    <form method="GET" action="{{ route('search') }}" accept-charset="UTF-8" class="navbar-form navbar-left hidden-sm hidden-md">
                        <div class="form-group">
                            <input class="form-control search-input mac-style" placeholder="搜索" name="q" type="text" value="{!! (Request::is('search*') && isset($query)) ? $query : ''  !!} ">
                        </div>
                    </form>
                @endif

                @guest
                    {{--Authenticaiton Links--}}
                    <li><a href="{{ route('login') }}">登录</a></li>
                    <li><a href="{{ route('register') }}">注册</a></li>
                @else
                    <li>
                        <a href="{{ route('topics.create') }}">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </a>
                    </li>
                    {{-- 标记消息通知 --}}
                    <li>
                        <a href="{{ route('notifications.index') }}" class="notifications-badge" style="margin-top: -2px">
                            <span class="badge badge-{{ Auth::user()->notification_count  >0 ? 'hint' : 'fade'  }}" title="消息提醒">
                                {{ Auth::user()->notification_count }}
                            </span>
                        </a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            <span class="user-avatar pull-left" style="margin-right:8px; margin-top:-5px;">
                                <img src="{{ auth()->user()->avatar }}" class="img-responsive img-circle" width="30px" height="30px">
                            </span>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            @can('manage_contents')
                                <li>
                                    <a href="{{ url(config('administrator.uri')) }}">
                                        <span class="glyphicon glyphicon-dashboard" aria-hidden="true"></span>
                                        管理后台
                                    </a>
                                </li>
                            @endcan
                            @if(Auth::user()->hasRole('Founder'))
                                <li>
                                    <a href="{{ url(config('redis-manager.base_path')) }}">
                                        <span class="glyphicon glyphicon-folder-open"></span>
                                        Redis管理
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('logs') }}">
                                        <span class="glyphicon glyphicon-list-alt">
                                            logs日志查看
                                        </span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{ route('users.show',Auth::id()) }}">
                                    <span class="glyphicon glyphicon-user"></span>
                                    个人中心
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.edit',Auth::id()) }}">
                                    <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                    编辑资料
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('logout') }}" id="login-out" data-lang-loginout="您真的要退出登录吗？">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    退出登录
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
