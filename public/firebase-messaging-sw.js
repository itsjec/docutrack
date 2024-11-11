// public/firebase-messaging-sw.js

importScripts('https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.9.1/firebase-messaging.js');

// Initialize Firebase with your config
firebase.initializeApp({
  apiKey: "AIzaSyBW8kkarFiFtWze41fLpoIZ2c0bISyKo0g",
  authDomain: "push-notif-7fe44.firebaseapp.com",
  projectId: "push-notif-7fe44",
  storageBucket: "push-notif-7fe44.firebasestorage.app",
  messagingSenderId: "7974205951",
  appId: "1:7974205951:web:0d31e67b4a887c13b44fca",
});

// Retrieve Firebase Messaging instance
const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage(function(payload) {
  console.log('Received background message:', payload);
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: payload.notification.icon
  };

  self.registration.showNotification(notificationTitle, notificationOptions);
});
