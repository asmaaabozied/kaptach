<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if (isset(auth('admin')->user()->adminable->logo))
                    @if(auth('admin')->user()->type == 'transfer_company')
                        <img src="{{url('uploads/companies/'.auth('admin')->user()->adminable->logo)}}"
                             class="img-circle"
                             alt="User Image">
                    @else
                        <img src="{{auth('admin')->user()->adminable->logo['original']}}" class="img-circle"
                             alt="User Image">
                    @endif
                @else
                    <img src="{{url('/assets/dist/img/user2-160x160.jpg')}}" class="img-circle"
                         alt="User Image">
                @endif
            </div>
            <div class="pull-left info">
                <p> {{auth('admin')->user()->adminable->name}}</p>
                {{--<a href="#"><i class="fa fa-circle text-success"></i> Online</a>--}}
            </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="{{__('inputs.search')}}...">
                <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        @if(auth('admin')->user()->type=='transfer_company')
            @include('companies.sidebar')
        @elseif(auth('admin')->user()->type=='hotel' || auth('admin')->user()->type=='tourism_company')
            @include('clients.sidebar')
        @endif
    </section>
    <!-- /.sidebar -->
</aside>