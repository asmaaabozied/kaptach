@extends('layouts.master')
@section('title','Languages')
@section('styles')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('assets/plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h2>Create Language</h2>
                </div>
                <div class="box-body">
                    {!! BootForm::model($language, 'edit', ['method'=>'put', 'route'=>['languages.update',$language]]) !!}
                    @foreach($language_files as $key => $files)
                        <h2>{{ucfirst($key)}} File</h2><br/>
                        @foreach($files as $k => $value)
                            {!! BootForm::input('text', $key.'['.$k.']',$value, null, $errors,['required']) !!}
                        @endforeach
                    @endforeach
                    {{--{!! BootForm::input('text', 'local_name', null, 'Name', $errors,['required','data-parsley-length'=>'[3,20]']) !!}--}}
                    {{--{!! BootForm::input('text', 'code', null, 'Code', $errors,['required','data-parsley-maxlength'=>'2']) !!}--}}
                    {{--{!! BootForm::select('direction', 'Direction', ['ltr'=>'ltr','rtl'=>'rtl'], null, $errors,['required']) !!}--}}
                    {{--<br>--}}
                    {!! BootForm::submit() !!}
                    {!! BootForm::close() !!}

                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{asset('assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
        $(function () {
            $('#example1').DataTable({});
        });
    </script>
    <!--bootbox -->
@endsection