<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- base:css -->
  <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DocuTrack Online </title>

  <!-- Manifest -->
  <link rel="manifest" href="/manifest.json">

  <!-- Favicon and PWA icons -->
  <link rel="icon" sizes="192x192" href="/icons/icon-192x192.png">
  <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">
  <link rel="icon" sizes="512x512" href="/icons/icon-512x512.png">
  <link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512x512.png">
  <meta name="theme-color" content="#007bff">
  <style>
    #particles-js {
      position: absolute;
      width: 100%;
      height: 100vh;
      top: 0;
      left: 0;
      z-index: -1;
      /* Ensure it stays behind the content */
      background-size: cover;
      background-position: center;
      background-color: #6C007C;
    }


    .content-wrapper {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .auth-form-light {
      background: rgba(255, 255, 255, 0.8);
      /* Make the form background slightly transparent */
      border-radius: 8px;
      z-index: 1;
      /* Ensure the form stays above the particles background */
      position: relative;
      /* Relative positioning to ensure proper stacking */
    }
  </style>
</head>

<body>
  <div id="particles-js"></div>
  <div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="row w-100 mx-0">
      <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
          <h4>Hello! let's get started</h4>
          <h6 class="font-weight-light">Sign in to continue.</h6>

          <?php if (session()->has('error')): ?>
            <div class="alert alert-danger" role="alert">
              <?= session('error') ?>
            </div>
          <?php endif; ?>
          <form id="loginForm" class="pt-3" action="<?= site_url('login') ?>" method="POST">

            <div class="form-group">
              <input type="text" class="form-control form-control-lg" id="emailOrUsername" name="emailOrUsername"
                placeholder="Email or Username">
            </div>
            <div class="form-group">
              <input type="password" class="form-control form-control-lg" id="exampleInputPassword1" name="password"
                placeholder="Password">
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN
                IN</button>
            </div>
            <!-- Link for creating an account -->
            <div class="text-center mt-4 font-weight-light">
              Don't have an account? <a href="register" class="text-primary">Create</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  </div>
  <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- base:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- endinject -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <script type="text/javascript" src="assets2/js/particles.js"></script>
  <script type="text/javascript" src="assets2/js/app.js"></script>

  <!-- endinject -->

  <script>
   if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/service-worker.js')
            .then(function(registration) {
                console.log('Service Worker registered with scope: ', registration.scope);
            }, function(err) {
                console.log('Service Worker registration failed: ', err);
            });
    });
}

  </script>
</body>

</html>