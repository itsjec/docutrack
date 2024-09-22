<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center" style="background: linear-gradient(135deg, #9220b9, #C36EB8);">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">
          <a class="navbar-brand brand-logo" href="<?= site_url('dashboard') ?>"><img src="assets/images/logo.png"/></a>
          <a class="navbar-brand brand-logo-mini" href="<?= site_url('dashboard') ?>"><img src="assets/images/logo.png" alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-profile dropdown">
          <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
              <span class="nav-profile-name">Admin</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="typcn typcn-cog-outline text-primary"></i>
                Settings
              </a>
              <a class="dropdown-item" href = "/">
                <i class="typcn typcn-eject text-primary"></i>
                Logout
              </a>
            </div>
          </li>
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
      </div>
    </nav>
    <!-- partial -->
    </nav>