<!-- /*
* Template Name: Property
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
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
    <link
      href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="assets/vendors/typicons/typicons.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <link rel="stylesheet" href="assets/css/vertical-layout-light/style.css">
  <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="assets2/fonts/icomoon/style.css" />
    <link rel="stylesheet" href="assets2/fonts/flaticon/font/flaticon.css" />

    <link rel="stylesheet" href="assets2/css/tiny-slider.css" />
    <link rel="stylesheet" href="assets2/css/aos.css" />
    <link rel="stylesheet" href="assets2/css/style.css" />

    <title>
      Property &mdash; Free Bootstrap 5 Website Template by Untree.co
    </title>
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

    <?php include ('include\navbar.php'); ?>

    <!-- Other HTML content above -->
    <div class="container" style="margin-top: 150px;"> <!-- Adjust the margin-top value as needed -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="table-responsive pt-3">
                        <table class="table table-striped project-orders-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Tracking Number</th>
                                    <th>Status</th>
                                    <th>Current Office</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documents as $document): ?>
                                    <tr>
                                        <td><?= $document->title ?></td>
                                        <td><?= $document->tracking_number ?></td>
                                        <td><?= $document->status ?></td>
                                        <td><?= $document->current_office ?></td>
                                        <td>
                                            <a href="#" class="btn btn-info btn-sm btn-icon-text">
                                                View
                                                <i class="typcn typcn-eye btn-icon-append"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Other HTML content below -->

    </div>
</div>


  
</div>

      </div>
      <!-- /.container -->
    </div>
    <!-- /.site-footer -->

    <!-- Preloader -->
    <div id="overlayer"></div>
    <div class="loader">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <script src="assets2/js/bootstrap.bundle.min.js"></script>
    <script src="assets2/js/tiny-slider.js"></script>
    <script src="assets2/js/aos.js"></script>
    <script src="assets2/js/navbar.js"></script>
    <script src="assets2/js/counter.js"></script>
    <script src="assets2/js/custom.js"></script>
  </body>
</html>
