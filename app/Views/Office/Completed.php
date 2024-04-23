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
  <!-- endinject -->
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
        <?php include ('main-include/completed.php'); ?>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
$('.delete-btn').on('click', function() {
      var documentId = $(this).data('document-id');
      var documentTitle = $(this).data('document-title');
      var trackingNumber = $(this).data('tracking-number');

      $('#documentTitleDelete').text(documentTitle);
      $('#trackingNumberDelete').text(trackingNumber);

      $('#confirmDeleteBtn').data('document-id', documentId);

      $('#deleteDocumentModal').modal('show');
  });

    $("#confirmDeleteBtn").click(function(event) {
      event.preventDefault(); // Prevent the default button behavior

      let docId = $(this).data("document-id");
      console.log("Document ID:", docId); // Log the document ID
      $.ajax({
          url: 'documents/update-document-deleted-status/' + docId + '/deleted',
          type: 'POST',
          success: function(response) {
              console.log(response);
              $("#deleteDocumentModal").modal('hide');
              location.reload(); // Reload the page
          },
          error: function(xhr, status, error) {
              console.error(xhr.responseText);
          }
      });
  });

  var currentDocumentId;

function updateRecipientId() {
    var documentId = $('.btn-send-out').data('document-id');
    var recipientId = $('#office_id').val(); 
    $('#document_id').val(documentId);
    $('#recipient_id').val(recipientId);
}

$(document).ready(function() {
    $('.btn-send-out').on('click', function() {
        currentDocumentId = $(this).data('document-id');
        updateRecipientId();
    });

    $('#confirmSendOutBtn').on('click', function() {
        var documentId = $('#document_id').val(); 
        var recipientId = $('#recipient_id').val(); 

        $.ajax({
            url: '<?= site_url('documents/update-document-recipient-and-status/') ?>' + documentId + '/' + recipientId + '/pending',
            type: 'POST',
            success: function(response) {
                console.log(response);
                $('#sendOutModal').modal('hide');
                location.reload(); // Reload the page
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});



</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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



  <!-- base:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/template.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="assets/js/dashboard.js"></script>
  <!-- End custom js for this page-->
</body>

</html>

