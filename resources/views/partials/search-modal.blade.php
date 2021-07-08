<div class="modal fade" id="searchModal"  role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            {!! Form::open(['method'=>'GET', 'route'=>$route]) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ $title }}</h4>
            </div>
            <div class="modal-body">
                {{$slot}}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary"> <i class="fa fa-search" aria-hidden="true"></i> {{__('buttons.search')}}</button>
                @php
                    $params = null;
                    if(is_array($route)){
                        $route_name = $route[0];
                        $params = count($route) > 1 ? $route[1]: null;
                    }
                    else{
                        $route_name = $route;
                    }
                        
                @endphp
                <a href="{{ route($route_name, $params) }}" class="btn btn-default"> <i class="fa fa-eraser" aria-hidden="true"></i> {{__('buttons.clear')}}</a>
                @if(!empty($export))
                    @if(in_array('xls', $export))
                        <button type="submit" name="export" value="xls" class="btn btn-primary"> <i class="fa fa-file-excel-o" aria-hidden="true"></i>{{__('buttons.export')}} </button>
                    @endif
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal"> <i class="fa fa-times" aria-hidden="true"></i>{{__('buttons.close')}}</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>