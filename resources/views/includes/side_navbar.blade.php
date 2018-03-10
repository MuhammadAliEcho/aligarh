<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <span>
                      <img alt="image" width="48px" height="48px" class="img-circle" src="{{ URL::to('img/avatar.jpg') }}" />
                    </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold text-capitalize">{{ Auth::user()->role }}</strong>
                    </span> <span class="text-muted text-xs block text-capitalize">{{ Auth::user()->name }}<b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ URL('user-settings') }}"><span class="fa fa-gear fa-spin"></span> User Settings</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ URL('logout') }}"><span class="fa fa-sign-out"></span> Logout</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    SMS
                </div>
            </li>
            <li>
                <a href="{{ URL('dashboard') }}" data-root="dashboard"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
            </li>

            @foreach(App\AdminContent::navigations() AS $navigation)
                @if($navigation->type == 'parent-content')
                    <li data-show="{{ Auth::user()->NavPrivileges($navigation->id, 'default') }}" >
                        <a href="{{ URL($navigation->root) }}" data-root="{{ $navigation->root }}"><i class="{{ $navigation->icon }}"></i> <span class="nav-label">{{ $navigation->label }}</span></a>
                    </li>
                @else
                    <li>
                        <a href="#"><i class="{{ $navigation->icon }}"></i> <span class="nav-label">{{ $navigation->label }}</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level collapse">
                        @foreach($navigation->child_content AS $child_content)
                            <li data-show="{{ Auth::user()->NavPrivileges($child_content->id, 'default') }}" >
                                <a href="{{ URL($child_content->root) }}" data-root="{{ $child_content->root }}">{{ $child_content->label }}</a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

        </ul>

    </div>
</nav>

<script type="text/javascript">
  $(document).ready(function(){
    $('[data-show=0]').addClass('hidden').parents('li').addClass('hidden');
    $('[data-show=1]').parents('li').removeClass('hidden');
    $('a[data-root="{{ $root['ctrl'] }}"]').parents('li').toggleClass('active');
//	  $('li.active').parents('li').toggleClass('active');
  });
</script>
