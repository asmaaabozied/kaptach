<ul class="sidebar-menu">
    <li {{ Request::is('dashboard**')  ? 'class=active' : '' }}>
        <a href="{{url('/dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>{{__('sidebar.dashboard')}}</span>
        </a>
    </li>
    <li {{ Request::is('my-clients**')  ? 'class=active' : '' }}>
        <a href="{{route('my-clients.index')}}">
            <i class="fa  fa-list-alt"></i>
            <span>{{__('sidebar.my_clients')}}</span>
        </a>
    </li>
    @if(@auth('admin')->user()->adminable->id == 1)
        <li {{ Request::is('companies**')  ? 'class=active' : '' }}>
            <a href="{{route('companies.index')}}">
                <i class="fa  fa-list-alt"></i>
                <span>{{__('sidebar.transfer_companies')}}</span>
            </a>
        </li>
    @endif
    <li {{ Request::is('/drivers**')  ? 'class=active' : '' }}>
        <a href="{{route('drivers.index')}}">
            <i class="fa fa-circle-o"></i>
            <span>{{__('sidebar.drivers')}}</span>
        </a>
    </li>

    <li {{ Request::is('/hosts**')  ? 'class=active' : '' }}>
        <a href="{{route('hosts.index')}}">
            <i class="fa fa-user"></i>
            <span>{{__('sidebar.hosts')}}</span>
        </a>
    </li>
    <li {{ Request::is('/guests**')  ? 'class=active' : '' }}>
        <a href="{{route('guests.index')}}">
            <i class="fa fa-circle-o"></i>
            <span>{{__('sidebar.guests')}}</span>
        </a>
    </li>
    <li {{ Request::is('/prices-list**')  ? 'class=active' : '' }}>
        <a href="{{route('prices-list')}}">
            <i class="fa  fa-list-alt"></i>
            <span>{{__('sidebar.prices')}}</span>
        </a>
    </li>

    {{--<li {{ Request::is('/shuttles**')  ? 'class=active' : '' }}>--}}
        {{--<a href="{{url('/shuttles')}}">--}}
            {{--<i class="fa fa-user"></i>--}}
            {{--<span>{{__('sidebar.shuttles')}}</span>--}}
        {{--</a>--}}
    {{--</li>--}}

    <li class="treeview {{ Request::is('/transfers**')  ? 'active' : '' }}">
        <a href="#">
            <i class="fa   fa-bus"></i>
            <span>{{__('sidebar.transfers')}}</span>
            <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {{ Request::is('/transfers')  ? 'class=active' : '' }}><a
                        href="{{route('transfers.index')}}"><i
                            class="fa  fa-bus"></i>{{__('sidebar.transfers')}}</a></li>
            <li {{ Request::is('transfers/store')  ? 'class=active' : '' }}><a
                        href="{{route('store.index')}}"><i
                            class="fa  fa-shopping-cart"></i>{{__('sidebar.sale_exchange')}}
                </a>
            </li>
        </ul>
    </li>

    <li class="treeview {{  Request::is('stations**') || Request::is('airports**') ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-files-o"></i>
            <span>{{__('sidebar.list')}}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @if(@auth('admin')->user()->adminable->id==1 && @auth('admin')->user()->type == 'transfer_company')
                <li {{ Request::is('stations**')  ? 'class=active' : '' }}><a
                            href="{{route('stations.index')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.stations')}}
                    </a>
                </li>
                <li {{ Request::is('airports**')  ? 'class=active' : '' }}><a
                            href="{{route('airports.index')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.airports')}}
                    </a>
                </li>
            @endif
        </ul>
    </li>
    <li class="treeview {{ Request::is('/car**')  ? 'active' : '' }}">
        <a href="#">
            <i class="fa  fa-car"></i>
            <span>{{__('sidebar.cars')}}</span>
            <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {{ Request::is('/cars**')  ? 'class=active' : '' }}><a href="{{route('cars.index')}}"><i
                            class="fa  fa-car"></i>{{__('sidebar.cars')}}</a></li>
            <li {{ Request::is('/carmodels**')  ? 'class=active' : '' }}><a
                        href="{{route('carmodels.index')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.car_models')}}
                </a>
            </li>
        </ul>
    </li>

    <li class="treeview {{ Request::is('/admins**') ||Request::is('/roles**')  ? 'active' : '' }}">
        <a href="#">
            <i class="fa fa-user"></i>
            <span>{{__('sidebar.admins')}}</span>
            <span class="pull-right-container">
                 <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <li {{ Request::is('/admins**')  ? 'class=active' : '' }}><a href="{{route('admins.index')}}"><i
                            class="fa fa-circle-o"></i>{{__('sidebar.admins')}}</a></li>
            <li {{ Request::is('/roles**')  ? 'class=active' : '' }}><a href="{{route('roles.index')}}"><i
                            class="fa fa-circle-o"></i>{{__('sidebar.roles')}}</a></li>
        </ul>
    </li>
    <li {{ Request::is('/payments**')  ? 'class=active' : '' }}>
        <a href="{{route('payments.index')}}">
            <i class="fa fa-user"></i>
            <span>{{__('sidebar.payments')}}</span>
        </a>
    </li>
    <li {{ Request::is('/invoices**')  ? 'class=active' : '' }}>
        <a href="{{route('invoices.index')}}">
            <i class="fa fa-user"></i>
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
            <li {{ Request::is('reports/annual**')  ? 'class=active' : '' }}><a href="{{route('annual_report')}}"><i
                            class="fa fa-circle-o"></i>{{__('sidebar.annual_report')}}</a>
            </li>
            {{--<li><a href="{{route('payments_report')}}"><i class="fa fa-circle-o"></i>Payments Report</a></li>--}}
            {{--<li><a href="{{route('transportation_report')}}"><i class="fa fa-circle-o"></i>Transportation Report</a></li>--}}
            <li {{ Request::is('/reports/clients**')  ? 'class=active' : '' }}><a
                        href="{{route('clients_balance_report')}}"><i class="fa fa-circle-o"></i>Clients Balance</a>
            </li>
            <li><a href="{{route('charts_report')}}"><i class="fa fa-circle-o"></i>{{__('sidebar.charts')}}</a></li>
        </ul>
    </li>
    <li {{ Request::is('notifications**')  ? 'class=active' : '' }}>
        <a href="{{ route('notifications.index') }}">
            <i class="fa fa-commenting-o"></i> <span>Notifications</span>
        </a>
    </li>
    @if(@auth('admin')->user()->adminable->id==1 && @auth('admin')->user()->type=='transfer_company')
        <li {{ Request::is('/languages**')  ? 'class=active' : '' }}>
            <a href="{{route('languages.index')}}">
                <i class="fa fa-user"></i>
                <span>{{__('sidebar.languages')}}</span>
            </a>
        </li>
    @endif
    <li class="treeview {{ Request::is('/settings**')  ? 'active' : '' }}">
        <a href="{{route('settings')}}">
            <i class="fa fa-fw fa-cog"></i>
            <span>{{__('sidebar.settings')}}</span>
        </a>
    </li>
</ul>