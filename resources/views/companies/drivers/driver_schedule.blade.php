@extends('layouts.master')
@section('title','Schedule')
@section('styles')
    <!-- fullCalendar 2.2.5-->
    <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/fullcalendar.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/fullcalendar/fullcalendar.print.css')}}" media="print">
    <!-- Bootstrap time Picker -->
    <link rel="stylesheet" href="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.css')}}">
@endsection
@section('content')

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-body no-padding">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div id="calendarModal" class="modal fade">
        {!! BootForm::open('create', ['url'=>route('drivers.create_schedule',$id),'id'=>'basic-form']) !!}
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 id="modalTitle" class="modal-title">Add
                        Schedule {!! BootForm::input('text', 'date', null, 'Date', $errors, ['readonly','class'=>'form-control','id'=>'date']) !!}
                    </h4>
                </div>
                <div id="modalBody" class="modal-body">
                    {!! BootForm::select('car_id','Cars',$cars->prepend('Select Car',''),null,$errors,['required','class'=>'form-control']) !!}
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label>Start shift time picker:</label>
                            <div class="input-group">
                                <input type="text" name="shift_start_time" id="starttime"
                                       class="form-control timepicker" required>

                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                    <div class="bootstrap-timepicker">
                        <div class="form-group">
                            <label>End shift time picker:</label>

                            <div class="input-group">
                                <input type="text" name="shift_end_time" id="endtime" class="form-control timepicker"
                                       required>

                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div>
                            <!-- /.input group -->
                        </div>
                        <!-- /.form group -->
                    </div>
                    {!! BootForm::checkbox('repeat','Add Working Hour daily for month',1) !!}

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    {!! BootForm::submit() !!}
                </div>
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- fullCalendar 2.2.5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="{{asset('assets/plugins/fullcalendar/fullcalendar.min.js')}}"></script>
    <!-- bootstrap time picker -->
    <script src="{{asset('assets/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script>
        $(function () {
            //Timepicker
            $(".timepicker").timepicker({
                showInputs: false,
                showMeridian: false
            });

            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function () {

                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    };

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 1070,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                });
            }

            /* initialize the calendar
             -----------------------------------------------------------------*/
            //Date for the calendar events (dummy data)
            var date = new Date();
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear();
            $('#calendar').fullCalendar({
                selectable: true,
                defaultView: 'month',
                timeFormat: 'H:mm',
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'

                },
                //Random default events
                events: [
                        @foreach($shifts as $shift)
                    {
                        title: '{{$employer->username}}',
                        start: '{{ $shift->shift_start_time }}',
                        end: '{{ $shift->shift_end_time }}',
                        url: '{{ route('drivers.edit_schedule', [$shift->id]) }}'

                    },
                    @endforeach
                ],
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
                drop: function (date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');

                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;
                    copiedEventObject.backgroundColor = $(this).css("background-color");
                    copiedEventObject.borderColor = $(this).css("border-color");

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }

                },
                select: function (startDate, endDate, jsEvent, view) {
                    var date = (new Date(startDate)).toISOString().slice(0, 10);
                    $('#date').val(date);
                    var startTime = startDate.format("H:mm");
                    var endTime = endDate.format('H:mm');
                    $('#starttime').timepicker('setTime', startTime);
                    $('#endtime').timepicker('setTime', endTime);
                    $('#calendarModal').modal();
                },

            });
        });
    </script>
@endsection