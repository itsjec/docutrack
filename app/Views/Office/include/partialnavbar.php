<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center" style="background: linear-gradient(135deg, #9220b9, #C36EB8);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo" href="<?= site_url('index') ?>"><img src="assets/images/logo.png" alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="<?= site_url('index') ?>"><img src="images/logo-mini.svg" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-profile dropdown">
          <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
          <?php if (!empty($user['picture_path'])): ?>
              <img src="<?= htmlspecialchars($user['picture_path']) ?>" alt="profile" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
          <?php else: ?>
              <img src="assets/images/faces/face5.jpg" alt="profile" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
          <?php endif; ?>
          <span class="nav-profile-name"><?= htmlspecialchars($office_name) ?></span>
      </a>
          <li class="nav-item nav-user-status dropdown">
              <p class="mb-0">Currently Logged In.</p>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
        <li class="nav-item nav-date dropdown">
          <a id="currentDate" class="nav-link d-flex justify-content-center align-items-center" href="javascript:;">
            <h6 class="date mb-0">Today: <span id="currentDateText"></span></h6>
            <i id="calendarIcon" class="typcn typcn-calendar"></i>
          </a>
        </li>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="typcn typcn-th-menu"></span>
        </button>
    </nav>
    <!-- partial -->
    </nav>