<ul class="sidebar-menu">
    <li class="header">MAIN NAVIGATION</li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{url('')}}"><i class="fa fa-circle-o"></i> Dashboard</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i>
            <span>Admins</span>
            <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{route('admins.index')}}"><i class="fa fa-circle-o"></i>Admins</a></li>
            <li><a href="{{route('roles.index')}}"><i class="fa fa-circle-o"></i>Roles</a></li>
        </ul>
    </li>
</ul>