{{-- Script hosted on running laravel-echo-server --}}
{{--<script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>--}}
<!-- jQuery 2.2.3 -->
<script src="{{asset('/assets/plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
@yield('appjs')
@yield('gmap')
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('/assets/bootstrap/js/bootstrap.min.js')}}"></script>
<!-- SlimScroll -->
<script src="{{asset('/assets/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{asset('/assets/plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('/assets/dist/js/app.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('/assets/dist/js/demo.js')}}"></script>

<script>
    $('#flash-overlay-modal').modal();
</script>
<script>
    function sendMarkRequest(id = null) {
        return $.ajax({
            type: 'POST',
            url: '{!! route('notifications.mark-as-read') !!}',
            headers: {
                "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
            },
            data: {
                id: id
            },
        });

    }

    $(function () {
        $('.mark-as-read').click(function () {
            var request = sendMarkRequest($(this).data('id'));
            request.done(function () {
                $(this).parents('li.alert').remove();
            });
        });
        $('#mark-all').click(function () {
            var request = sendMarkRequest();
            request.done(function () {
                $('li.alert').remove();
            })
        });
    });
</script>
@yield('scripts')
