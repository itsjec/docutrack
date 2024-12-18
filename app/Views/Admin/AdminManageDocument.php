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
        <?php include ('main-include/manageguestdocument.php'); ?>
            </div>
            <!-- content-wrapper ends -->
            <!-- partial -->
        </div>
        <!-- main-panel ends -->
        </div>
</div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->



  <script>
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

    $(document).ready(function() {
        function displaySuccessModal(trackingNumber) {
            $('#trackingNumber').text(trackingNumber);
            $('#successModal').modal('show');
        }
    });
    </script>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    // Get current date
    const currentDate = new Date();
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const formattedDate = monthNames[currentDate.getMonth()] + " " + currentDate.getDate();
    
    // Update the date in the element
    document.getElementById("currentDateText").textContent = formattedDate;

    // Add click event listener to the calendar icon
    document.getElementById("calendarIcon").addEventListener("click", function() {
      // Handle calendar icon click event here
      alert("Calendar icon clicked!");
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

$(document).ready(function () {
        $('.edit-btn').click(function () {
            var documentId = $(this).data('documentid');
            var title = $(this).data('title');
            var senderOfficeId = $(this).data('sender-office-id');
            var recipientOfficeId = $(this).data('recipient-office-id');
            var classification = $(this).data('classification');
            var subClassification = $(this).data('sub-classification');
            var dateOfDocument = $(this).data('date-of-document');
            var action = $(this).data('action');
            var description = $(this).data('description');

            $('#editDocumentId').val(documentId);
            $('#editTitle').val(title);
            $('#sender_office_id').val(senderOfficeId);
            $('#editRecipientOfficeId').val(recipientOfficeId);
            $('#editClassification').val(classification);
            $('#editSubClassification').val(subClassification);
            $('#editDateOfDocument').val(dateOfDocument);
            $('#editAction').val(action);
            $('#editDescription').val(description);

            // Show the modal
            $('#editDocumentModal').modal('show');
        });
    });
  </script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <script>
    let table = new DataTable('#managedocument', {
        "paging": true,          
        "pageLength": 5,         
        "lengthMenu": [5],       
        "info": true,            
        "lengthChange": false,
        "searching": false,   
    });
    </script>
     <!-- base:js
    <!-- <script src="assets/vendors/js/vendor.bundle.base.js"></script> -->
    <!-- endinject -->
    <!-- Plugin js for this page-->
    <!-- <script src="assets/vendors/chart.js/Chart.min.js"></script> -->
    <!-- End plugin js for this page-->
    <!-- inject:js -->
    <!-- <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/template.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script> -->
    <!-- endinject -->
  <!-- Custom js for this page-->
   <!--<script src="assets/js/dashboard.js"></script>-->
  <!-- End custom js for this page-->
</body>

</html>

