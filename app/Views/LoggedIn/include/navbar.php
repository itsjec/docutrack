<nav class="site-nav">
      <div class="container">
        <div class="menu-bg-wrap">
          <div class="site-navigation">
            <a href="index.html" class="logo m-0 float-start">Property</a>

            <ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu float-end">
              <li class="active"><a href="index.html">Home</a></li>
              <li><a href="<?= site_url('transactions') ?>">Transactions</a></li>
              <li class="has-children">
                <a href="properties.html">Profile</a>
                <ul class="dropdown">
                  <li><a href="<?= site_url('userindex') ?>">Log Out</a></li>
                </ul>
              </li>
            </ul>
            <a
              href="#"
              class="burger light me-auto float-end mt-1 site-menu-toggle js-menu-toggle d-inline-block d-lg-none"
              data-toggle="collapse"
              data-target="#main-navbar"
            >
              <span></span>
            </a>
          </div>
        </div>
      </div>
    </nav>