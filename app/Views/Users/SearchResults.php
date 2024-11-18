<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Untree.co" />
  <link rel="shortcut icon" href="favicon.png" />

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap5" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />

  <link rel="stylesheet" href="assets2/fonts/icomoon/style.css" />
  <link rel="stylesheet" href="assets2/fonts/flaticon/font/flaticon.css" />
  <link rel="stylesheet" href="assets2/css/tiny-slider.css" />
  <link rel="stylesheet" href="assets2/css/aos.css" />
  <link rel="stylesheet" href="assets2/css/style.css" />
  <link rel="stylesheet" href="assets2/css/Results.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    #particles-js {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      background-color: #6c007c;
    }

    .banner {
      position: relative;
      z-index: 1;
      color: #fff;
      padding: 20px;
    }

    .step {
      position: relative;
      padding: 20px;
      text-align: center;
      cursor: default;
      transition: none;
    }
  </style>
</head>

<body>
  <div class="site-mobile-menu site-navbar-target">
    <div class="site-mobile-menu-header">
      <div class="site-mobile-menu-close">
        <span class="icofont-close js-menu-toggle"></span>
      </div>
    </div>
    <div class="site-mobile-menu-body"></div>
  </div>

  <?php include('main-include/searchresults.php'); ?>
  <div id="overlayer"></div>
  <div class="loader">
    <div class="spinner-border" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-app.js"></script>
  <script src="https://www.gstatic.com/firebasejs/8.9.1/firebase-messaging.js"></script>
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

    // Request permission and get the token on page load
    window.onload = function () {
      // Extract the query parameter 'number' from the URL
      const urlParams = new URLSearchParams(window.location.search);
      const trackingNumber = urlParams.get('number');

      console.log('Window loaded, tracking number:', trackingNumber);

      // Check if the tracking number exists
      if (trackingNumber) {
        console.log('Tracking number found:', trackingNumber);

        // Firebase messaging code
        console.log('Requesting permission for Firebase messaging...');
        messaging.requestPermission()
          .then(() => messaging.getToken())
          .then((token) => {
            console.log('Generated FCM Token:', token);

            // Send both the tracking number and token to the server
            fetch(`/store-document-token`, {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({ tracking_number: trackingNumber, token: token })
            })
              .then(response => response.json())
              .then(data => {
                console.log('Response from server:', data);
                if (data.success) {
                  console.log('Document stored and token assigned successfully!');
                } else {
                  console.error('Error:', data.message);
                }
              })
              .catch(error => {
                console.error('Error in storing document:', error);
              });
          })
          .catch((err) => {
            console.log('Permission denied or error occurred:', err);
          });
      } else {
        console.warn('No tracking number found in the query parameters.');
      }
    };


  </script>

  <script src="assets2/js/bootstrap.bundle.min.js"></script>
  <script src="assets2/js/tiny-slider.js"></script>
  <script src="assets2/js/aos.js"></script>
  <script src="assets2/js/navbar.js"></script>
  <script src="assets2/js/counter.js"></script>
  <script src="assets2/js/custom.js"></script>
  <script type="text/javascript" src="assets2/js/particles.js"></script>
  <script type="text/javascript" src="assets2/js/app.js"></script>
</body>

</html>