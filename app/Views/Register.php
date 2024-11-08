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
    <!-- endinject -->
    <style>
  #particles-js {
      position: absolute;
      width: 100%;
      height: 100vh;
      top: 0;
      left: 0;
      z-index: -1; /* Ensure it stays behind the content */
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
      background: rgba(255, 255, 255, 0.8); /* Make the form background slightly transparent */
      border-radius: 8px;
      z-index: 1; /* Ensure the form stays above the particles background */
      position: relative; /* Relative positioning to ensure proper stacking */
    }
    </style>
</head>

<body>
<body>
<div id="particles-js"></div>
    <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
            <div class="col-lg-4 mx-auto">
                <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                    <h4>New here?</h4>
                    <h6 class="font-weight-light">Signing up is easy. It only takes a few steps</h6>
                    <form class="pt-3" action="<?= site_url('register') ?>" method="POST">
                    <?php if (isset($validation)) : ?>
                        <div class="alert alert-danger">
                            <?= $validation->getError('email') ?>
                        </div>
                    <?php endif; ?>

                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="Password">
                            <div class="progress mt-2" style="height: 5px;">
                                <div id="password-strength-meter" class="progress-bar" role="progressbar"></div>
                            </div>
                            <small class="text-muted">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, and one number.</small>
                        </div>
                        <div class="mt-3">
                            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN UP</button>
                        </div>
                        <div class="text-center mt-4 font-weight-light">
                            Already have an account? <a href="/" class="text-primary">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- container-scroller -->
    <!-- base:js -->
    <script>
    document.getElementById('password').addEventListener('input', function () {
        var password = document.getElementById('password').value;
        var meter = document.getElementById('password-strength-meter');
        var strength = 0;

        // Minimum length check
        if (password.length >= 8) {
            strength += 1;
        }

        // Contains lowercase letter check
        if (password.match(/[a-z]/)) {
            strength += 1;
        }

        // Contains uppercase letter check
        if (password.match(/[A-Z]/)) {
            strength += 1;
        }

        // Contains number check
        if (password.match(/[0-9]/)) {
            strength += 1;
        }

        // Update progress bar
        switch (strength) {
            case 0:
                meter.style.width = '0%';
                break;
            case 1:
                meter.style.width = '25%';
                break;
            case 2:
                meter.style.width = '50%';
                break;
            case 3:
                meter.style.width = '75%';
                break;
            case 4:
                meter.style.width = '100%';
                break;
        }
    });
</script>
<script src="assets/vendors/js/vendor.bundle.base.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
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
</body>

</html>
