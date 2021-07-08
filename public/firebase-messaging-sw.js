importScripts('https://www.gstatic.com/firebasejs/7.14.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/7.14.2/firebase-messaging.js');
/*Update this config*/
var config = {
    apiKey: "AIzaSyBOq8dAVl2ZjP9K7DKyOxwX24GM5zwo3_4",
    authDomain: "kaptan-vip-37609.firebaseapp.com",
    databaseURL: "https://kaptan-vip-37609.firebaseio.com",
    projectId: "kaptan-vip-37609",
    storageBucket: "kaptan-vip-37609.appspot.com",
    messagingSenderId: "1009968965988",
    appId: "1:1009968965988:web:42c33ffdc533afa98bb271",
    measurementId: "G-FLBQN570KF"
};
firebase.initializeApp(config);

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    console.log('Message received: ', payload);
    const parsedJSON = JSON.parse(payload.data['actions-data']);
    console.log('Actions:', payload);

    // Customize notification here
    const notificationTitle = payload.data.title;
    const notificationOptions = {
        body: payload.data.message,
        icon: '/icon.jpg',
        actions: parsedJSON.actions,
        url: self.location.origin,
        id: payload.data.id,
        transfer_id: payload.data.transfer_id
    };

    self.addEventListener('notificationclick', function(event) {
        event.notification.close();
        if (!event.action) {
            // Was a normal notification click
            console.log('Notification Click.');
            return;
        }

        switch (event.action) {

            case 'transfers':
                // This looks to see if the current is already open and
                // focuses if it is
                event.waitUntil(clients.matchAll({
                    type: "window"
                }).then(function (clientList) {
                    for (var i = 0; i < clientList.length; i++) {
                        var client = clientList[i];

                        if (client.url == '/' && 'focus' in client)
                            return client.focus();
                    }
                    if (clients.openWindow)
                        return clients.openWindow('/transfers/show/'+payload.data.transfer_id);
                }));
                break;
            default:
                console.log("Unknown action clicked: '${event.action}'");
                break;
        }
    });
    return self.registration.showNotification(notificationTitle,
        notificationOptions);
});
// var listener = new BroadcastChannel('listener');
// listener.postMessage('It works !!');
// [END background_handler]

