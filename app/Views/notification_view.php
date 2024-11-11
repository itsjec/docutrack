<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Push Notifications</title>
    <script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-messaging.js"></script>
</head>

<body>
    <h1>Push Notification Examplesssssssssssasasasa</h1>

    <button onclick="generateToken()">Generate Token</button>
    <button onclick="sendNotification()">Send Notification</button>

    <br><br>
    <label for="token">FCM Token:</label>
    <input type="text" id="token" placeholder="Enter FCM token here" />

    <script>
        // Firebase configuration (replace with your actual Firebase config)
        var firebaseConfig = {
            apiKey: 'AIzaSyBW8kkarFiFtWze41fLpoIZ2c0bISyKo0g',
            authDomain: 'push-notif-7fe44.firebaseapp.com',
            projectId: 'push-notif-7fe44',
            storageBucket: 'push-notif-7fe44.firebasestorage.app',
            messagingSenderId: '7974205951',
            appId: '1:7974205951:web:0d31e67b4a887c13b44fca',
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();

        // Request permission to send notifications
        function requestPermission() {
            return messaging.requestPermission()
                .then(() => messaging.getToken())
                .then((token) => {
                    document.getElementById('token').value = token;
                })
                .catch((err) => {
                    console.log('Permission denied', err);
                });
        }

        // Generate FCM token (when clicking "Generate Token" button)
        function generateToken() {
            requestPermission();
        }

        // Send push notification (when clicking "Send Notification" button)
        // Send push notification (when clicking "Send Notification" button)
        function sendNotification() {
    var token = document.getElementById('token').value;
    if (!token) {
        alert('Please generate a token first!');
        return;
    }

    // Log the token to the console for debugging
    console.log('FCM Token:', token);

    // Send the token to the server
    fetch('/notification/send_notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ token: token })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Notification sent successfully!');
        } else {
            alert('Failed to send notification: ' + data.message);
            console.error('Detailed response:', data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error sending notification.');
    });
}


    </script>
</body>

</html>
