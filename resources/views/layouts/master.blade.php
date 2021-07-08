<!DOCTYPE html>
<html>
<head>
    @include('partials.head')
</head>
<!-- ADD THE CLASS fixed TO GET A FIXED HEADER AND SIDEBAR LAYOUT -->
<!-- the fixed layout is not compatible with sidebar-mini -->
<body class="hold-transition skin-blue fixed sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

    @include('partials.header')
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <!-- =============================================== -->

    <!-- Left side column. contains the sidebar -->
@include('partials.navbar')

<!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="app">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <small></small>
            </h1>
            {{--<ul class="breadcrumb pull-right">--}}
            {{--<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>--}}
            {{--<li><a href="#">Layout</a></li>--}}
            {{--<li class="active">Fixed</li>--}}
            {{--</ul>--}}
            @include('flash::message')
        </section>

        <!-- Main content -->
        <section class="content" id="main-content">
            @yield('content')
        </section>
    {{--<div id="messages"></div>--}}
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>{{__('pages.version')}}</b> 1.x
        </div>
        <strong>Copyright &copy; {{date('Y')}} <a
                    href="http://kaptan-vip.com/">KAPTAN</a>.</strong> {{__('pages.all_right')}}
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Create the tabs -->
        <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
            <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
            <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Home tab content -->
            <div class="tab-pane" id="control-sidebar-home-tab">
                <h3 class="control-sidebar-heading">Recent Activity</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                <p>Will be 23 on April 24th</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-user bg-yellow"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                <p>New phone +1(800)555-1234</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                <p>nora@example.com</p>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <i class="menu-icon fa fa-file-code-o bg-green"></i>

                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                <p>Execution time 5 seconds</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

                <h3 class="control-sidebar-heading">Tasks Progress</h3>
                <ul class="control-sidebar-menu">
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Custom Template Design
                                <span class="label label-danger pull-right">70%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Update Resume
                                <span class="label label-success pull-right">95%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Laravel Integration
                                <span class="label label-warning pull-right">50%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)">
                            <h4 class="control-sidebar-subheading">
                                Back End Framework
                                <span class="label label-primary pull-right">68%</span>
                            </h4>

                            <div class="progress progress-xxs">
                                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                            </div>
                        </a>
                    </li>
                </ul>
                <!-- /.control-sidebar-menu -->

            </div>
            <!-- /.tab-pane -->
            <!-- Stats tab content -->
            <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
            <!-- /.tab-pane -->
            <!-- Settings tab content -->
            <div class="tab-pane" id="control-sidebar-settings-tab">
                <form method="post">
                    <h3 class="control-sidebar-heading">General Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Report panel usage
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Some information about this general settings option
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Allow mail redirect
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Other sets of options are available
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Expose author name in posts
                            <input type="checkbox" class="pull-right" checked>
                        </label>

                        <p>
                            Allow the user to show his name in blog posts
                        </p>
                    </div>
                    <!-- /.form-group -->

                    <h3 class="control-sidebar-heading">Chat Settings</h3>

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Turn off notifications
                            <input type="checkbox" class="pull-right">
                        </label>
                    </div>
                    <!-- /.form-group -->

                    <div class="form-group">
                        <label class="control-sidebar-subheading">
                            Delete chat history
                            <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                        </label>
                    </div>
                    <!-- /.form-group -->
                </form>
            </div>
            <!-- /.tab-pane -->
        </div>
    </aside>

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
</div>

<!-- ./wrapper -->
@include('partials.footer')
@yield('modals')
@if(auth('admin')->user()->adminable->type=='transfer')
    <script src="https://www.gstatic.com/firebasejs/7.14.2/firebase.js"></script>
    <script>
        // Your web app's Firebase configuration
        var firebaseConfig = {
            apiKey: "AIzaSyBOq8dAVl2ZjP9K7DKyOxwX24GM5zwo3_4",
            authDomain: "kaptan-vip-37609.firebaseapp.com",
            databaseURL: "https://kaptan-vip-37609.firebaseio.com",
            projectId: "kaptan-vip-37609",
            storageBucket: "kaptan-vip-37609.appspot.com",
            messagingSenderId: "1009968965988",
            appId: "1:1009968965988:web:42c33ffdc533afa98bb271",
            measurementId: "G-FLBQN570KF"
        };
        // Initialize Firebase

        firebase.initializeApp(firebaseConfig);
        firebase.analytics();

        const messaging = firebase.messaging();

        messaging.requestPermission()
            .then(function () {
                console.log('Notification permission granted.');
                return messaging.getToken();
            })
            .then(function (token) {
                console.log(token); // Display user token
                $.ajax({
                    type: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": "<?php echo csrf_token(); ?>"
                    },
                    url: '{!! route('devices.store') !!}',
                    data: {token: token, platform: 'web'},
                    success: function (data) {
                        console.log(data);
                    }
                });
            })
            .catch(function (err) { // Happen if user deney permission
                console.log('Unable to get permission to notify.', err);
            });

        messaging.onMessage(function (payload) {
            console.log('onMessage', payload);
            const parsedJSON = JSON.parse(payload.data['actions-data']);
            const notificationOptions = {
                body: payload.data.message,
                icon: '/icon.jpg',
                actions: parsedJSON.actions,
                url: self.location.origin,
                id: payload.data.id,
                transfer_id: payload.data.transfer_id
            };
            navigator.serviceWorker.getRegistration('/firebase-cloud-messaging-push-scope')
                .then(function (registration) {
                    registration.showNotification(
                        payload.data.title,
                        notificationOptions
                    )
                });
            appendMessage(payload);

        });

        // Add a message to the messages element.
        function appendMessage(payload) {

            // Customize notification here

            const messagesElement = document.querySelector('#messages');
            const dataElement = document.createElement('li');
            dataElement.innerHTML = "<a href='" + self.location.origin + "/transfers/show/" + payload.data.transfer_id + "'><i class='fa fa-car text-yellow'> </i>"
                + payload.data.message + "</a>";
            messagesElement.appendChild(dataElement);
            //count notification
            const count = document.querySelector('#notification_count');
            const li_count = document.querySelector('#li_notification');
            var counter = 1;
            if (count.innerText != '') {
                counter = +counter + +count.innerText;
            }
//        count.append('');
            count.textContent = counter;
            li_count.textContent = 'You have ' + counter + ' notifications';
            console.log(counter);

        }

        //    var listener = new BroadcastChannel('listener');
        //    listener.onmessage = function(e) {
        //        console.log('Got message from service worker',e);
        //    };
    </script>
@endif
</body>
</html>
