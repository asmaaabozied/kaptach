<ul class="sidebar-menu">
    <li {{ Request::is('dashboard**')  ? 'class=active' : '' }}>
        <a href="{{url('/clients/dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>{{__('sidebar.dashboard')}}</span>
        </a>
    </li>
    <li {{ Request::is('clients/transfers**')  ? 'class=active' : '' }}>
        <a href="{{route('clients.transfers.index')}}">
            <i class="fa fa-bus"></i> <span>{{__('sidebar.transfers')}}</span>
        </a>
    </li>
    {{--<li class="treeview">--}}
        {{--<a href="{{url('hotel/shuttles')}}">--}}
            {{--<i class="fa fa-dashboard"></i> <span>{{__('sidebar.shuttles')}}</span>--}}
            {{--<span class="pull-right-container">--}}
                  {{--<i class="fa fa-angle-left pull-right"></i>--}}
                {{--</span>--}}
        {{--</a>--}}
        {{--<ul class="treeview-menu">--}}
            {{--<li><a href="{{url('hotel/shuttles')}}"><i class="fa fa-circle-o"></i> {{__('sidebar.shuttles')}}</a></li>--}}
        {{--</ul>--}}
    {{--</li>--}}
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user"></i>
            <span>{{__('sidebar.admins')}}</span>
            <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="{{route('admins.index')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.admins')}}</a></li>
            <li><a href="{{route('roles.index')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.roles')}}</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="{{route('clients.payments.index')}}">
            <i class="fa fa-list-alt"></i>
            <span>{{__('sidebar.payments')}}</span>
        </a>
    </li>
    <li class="treeview">
        <a href="{{route('clients.invoices.index')}}">
            <i class="fa fa-list-alt"></i>
            <span>{{__('sidebar.invoices')}}</span>
        </a>
    </li>
    <li class="treeview {{ Request::is('reports**')  ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-fw fa-file-pdf-o"></i>
            <span>{{__('sidebar.reports')}}</span>
            <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {{ Request::is('reports/annual**')  ? 'class=active' : '' }}><a href="{{route('annual_report')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.annual_report')}}</a>
            </li>
            {{--<li><a href="{{route('payments_report')}}"><i class="fa fa-circle-o"></i>Payments Report</a></li>--}}
            {{--<li><a href="{{route('transportation_report')}}"><i class="fa fa-circle-o"></i>Transportation Report</a></li>--}}
            <li><a href="{{route('charts_report')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.charts')}}</a></li>
        </ul>
    </li>
    <li class="treeview">
        <a href="{{route('prices.index')}}">
            <i class="fa fa-list-alt"></i>
            <span>{{__('sidebar.prices')}}</span>
        </a>
    </li>
    <li class="treeview">
        <a href="{{route('client_settings')}}">
            <i class="fa fa-fw fa-cog"></i>
            <span>{{__('sidebar.settings')}}</span>
        </a>
    </li>
</ul>