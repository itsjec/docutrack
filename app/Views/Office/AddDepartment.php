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
  <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Include Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
  <!-- Bootstrap Datepicker CSS -->
   
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">


<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</head>
<body>
  <div class="container-scroller">
  <?php include ('include/partialnavbar.php'); ?>
      <!-- partial:partials/_settings-panel.html -->
      <div class="container-fluid page-body-wrapper">
        <div class="theme-setting-wrapper">
        <?php include ('include/setting.php'); ?>
        <!-- partial -->
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <?php include ('include/partialsidebar.php'); ?>
        </nav>
        <!-- partial -->
        <div class="main-panel">
        <?php include ('main-include/adddepartmentdocument.php'); ?>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
        </div>
</div>
    <!-- page-body-wrapper ends -->
  </div>
  <script>
        $('#addDocumentBtn').click(function () {
            $('#addDocumentModal').modal('show');
        });

        $(document).ready(function() {
        $('#classification').change(function() {
            var classification = $(this).val();
            var subClassificationSelect = $('#sub-classification');

            subClassificationSelect.html('<option value="" disabled selected>Loading...</option>');

            $.ajax({
                url: '<?= site_url('documents/getSubClassifications') ?>',
                type: 'POST',
                dataType: 'json',
                data: { classification: classification },
                success: function(response) {
                    subClassificationSelect.html('<option value="" disabled selected>Select Sub-Classification</option>');
                    $.each(response, function(index, subClassification) {
                        subClassificationSelect.append('<option value="' + subClassification.sub_classification + '">' + subClassification.sub_classification + '</option>');
                    });
                },
                error: function() {
                    subClassificationSelect.html('<option value="" disabled selected>Error loading sub-classifications</option>');
                }
            });
        });
    });

    $('.view-versions-btn').on('click', function() {
    var title = $(this).data('title');
    $.ajax({
        url: '<?= base_url('documents/fetchVersionsByTitle') ?>',
        method: 'GET',
        data: { title: title },
        success: function(response) {
            var versions = JSON.parse(response);
            var modalBody = $('#documentVersionsBody');
            modalBody.empty(); // Clear existing content

            versions.forEach(function(version) {
                var card = $('<div class="col-md-3">');
                var innerCard = $('<div class="card">');
                var cardBody = $('<div class="card-body">');
                var qrCodeURL = version.qr_code_url;

                innerCard.append(qrCodeURL); // Append the QR code URL
                cardBody.append('<h5 class="card-title">' + version.tracking_number + ' (' + version.version_number + ')</h5>');
                cardBody.append('<p class="card-text">' + version.title + ' ( v' + version.version_number + ')</p>');
                cardBody.append('<button class="btn btn-primary" onclick="printQR(`' + qrCodeURL + '`, \'' + version.tracking_number + '\')">Print</button>');
                cardBody.append('<button class="btn btn-primary" onclick="copyToClipboard(\'' + version.tracking_number + '\')">Copy</button>');

                innerCard.append(cardBody);
                card.append(innerCard);
                modalBody.append(card);
            });
        }
    });
});



    </script>

<script>
$('.delete-btn').on('click', function() {
    var documentId = $(this).data('document-id');
    $('#delete-btn').data('document-id', documentId);
    $('#deleteDocumentModal').modal('show');
});

$("#delete-btn").click(function(event) {
    event.preventDefault();

    let docId = $(this).data("document-id");

    $.ajax({
        url: 'admin/update-document-deleted-status/' + docId + '/deleted',
        type: 'POST',
        success: function(response) {
            console.log(response);
            $("#deleteDocumentModal").modal('hide');
            location.reload();
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
});
  </script>

  <!-- base:js -->
  <!--<script src="assets/vendors/js/vendor.bundle.base.js"></script>-->
  <!-- endinject -->
  <!-- Plugin js for this page-->
   <!--<script src="assets/vendors/chart.js/Chart.min.js"></script>-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
   <!--<script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>-->
  <!-- endinject -->
  <!-- Custom js for this page-->
 <!-- <script src="assets/js/dashboard.js"></script>-->
  <!-- End custom js for this page-->
</body>

</html>

