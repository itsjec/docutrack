<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <title>DocuTrack Online | Reset Password</title>
  <style>
    #particles-js {
      position: absolute;
      width: 100%;
      height: 100vh;
      top: 0;
      left: 0;
      z-index: -1;
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
      border-radius: 8px;
      z-index: 1;
      position: relative;
    }

    /* Modal Styling */
    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.7);
      justify-content: center;
      align-items: center;
      padding: 20px;
    }

    .modal-content {
      background-color: #fff;
      border-radius: 10px;
      padding: 30px;
      max-width: 400px;
      width: 100%;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      position: relative;
    }

    .modal-content h2 {
      margin-bottom: 20px;
      font-family: 'Oswald', sans-serif;
      color: #333;
    }

    .modal-content label {
      display: block;
      margin-bottom: 10px;
      color: #666;
      font-weight: bold;
      text-align: left;
    }

    .modal-content input[type=text],
    .modal-content input[type=email],
    .modal-content input[type=password] {
      width: calc(100% - 20px);
      padding: 10px;
      margin-bottom: 20px;
      border-radius: 20px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      transition: border-color 0.3s;
    }

    .modal-content button[type=submit] {
      width: 100%;
      padding: 12px;
      background-color: #a86add;
      color: white;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 18px;
    }

    .close {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      position: absolute;
      right: 10px;
      top: 10px;
      cursor: pointer;
    }

  </style>
</head>
<body>
  <div id="particles-js"></div>
  <div class="content-wrapper d-flex align-items-center auth px-0">
    <div class="row w-100 mx-0">
      <div class="col-lg-4 mx-auto">
        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
          <h4>Reset Your Password</h4>
          <h6 class="font-weight-light">Please choose a new password.</h6>

          <?php if (session()->has('error')): ?>
            <div class="alert alert-danger" role="alert">
              <?= session('error') ?>
            </div>
          <?php endif; ?>
          <?php if (session()->has('success')): ?>
            <div class="alert alert-success" role="alert">
              <?= session('success') ?>
            </div>
          <?php endif; ?>

          <form id="resetPasswordForm" method="POST">
          <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">
            <div class="form-group">
              <label for="newPassword">New Password</label>
              <input type="password" class="form-control form-control-lg" id="newPassword" name="newPassword" placeholder="Enter new password" required>
            </div>

            <div class="form-group">
              <label for="confirmNewPassword">Confirm Password</label>
              <input type="password" class="form-control form-control-lg" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm new password" required>
            </div>

            <div class="mt-3">
              <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">Reset Password</button>
            </div>
          </form>

          <div class="text-center mt-4 font-weight-light">
            <a href="<?= site_url('/') ?>" class="text-primary">Back to Login</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- OTP Modal -->
  <div class="modal" id="otpPasswordModal">
    <div class="modal-content">
      <span class="close" id="closeModal">&times;</span>
      <h2>Check your email for OTP code to confirm password reset</h2>
      <h2>Enter OTP</h2>
      <form id="otpPasswordForm" method="post" autocomplete="off">
        <label for="otp">OTP</label>
        <input type="text" id="otpInput" name="otpInput" placeholder="Enter OTP" required>
        <span class="text-danger"><?= isset($validation) ? $validation->getError('otp') : ''; ?></span>

        <button type="submit">Verify OTP</button>
      </form>
    </div>
  </div>

  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <script type="text/javascript" src="assets2/js/particles.js"></script>
  <script type="text/javascript" src="assets2/js/app.js"></script>

  <script>
    $(document).ready(function() {

      $('#closeModal').click(function() {
        $('#otpPasswordModal').css('display', 'none');
      });
    });
  </script>

  <script>
           document.getElementById('resetPasswordForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            const user_id = document.getElementById('user_id').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmNewPassword').value;

            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');

            if (confirmPassword != newPassword) {
                alert('Passwords do not match.');
                return;
            }

            fetch('<?= base_url('/admin-check-password-reset') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: new URLSearchParams({
                    user_id: user_id,
                    password: newPassword,
                    token: token
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Show the OTP modal
                        document.getElementById('otpPasswordModal').style.display = 'flex';
                    } else {
                        alert(data.message || 'Reset Password Failed. Please try again later.');
                    }
                })
                .catch(error => console.error('Error:', error));
        });

        document.getElementById('otpPasswordForm').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            const user_id = document.getElementById('user_id').value;
            const otpInput = document.getElementById('otpInput').value;
            const newPassword = document.getElementById('newPassword').value;
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');


            fetch('<?= base_url('/admin-confirm-password-reset') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: new URLSearchParams({
                    user_id: user_id,
                    password: newPassword,
                    token: token,
                    otp: otpInput
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'error') {
                        alert(data.message || 'Reset Password Failed. Please try again later.');
                    }

                    if (data.status ==='success') {
                        alert('Password reset successfully. You can now login with your new password.');
                        window.location.href = '<?= base_url()?>';
                    }
                })
                .catch(error => console.error('Error:', error));
        });

  </script>

</body>
</html>
