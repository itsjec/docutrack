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
        <?php include ('main-include/manageoffice.php'); ?>
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
$('#addOfficeBtn').click(function () {
    var officeName = $('#officeName').val();
    $('#addOfficeModal').modal('hide');
});

</script>
<!-- jQuery (should be placed before Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS (make sure to use Bootstrap 4) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
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
    $(document).ready(function () {
        // Open the edit modal and populate the form fields with the office details
        $('.edit-btn').click(function () {
            var officeId = $(this).data('office-id');
            var officeName = $(this).data('office-name');

            $('#editOfficeId').val(officeId);
            $('#editOfficeName').val(officeName);

            // Show the edit modal
            $('#editOfficeModal').modal('show');
        });

        // Handle the form submission for updating office name (close modal after submission)
        $('#editOfficeForm').submit(function (e) {
            e.preventDefault(); // Prevent form from submitting the default way

            var officeId = $('#editOfficeId').val();
            var officeName = $('#editOfficeName').val();

            // Example AJAX call to update office name
            $.ajax({
                url: '<?= base_url('updateOfficeName') ?>',
                type: 'POST',
                data: {
                    officeId: officeId,
                    officeName: officeName
                },
                success: function (response) {
                    // On success, show success message inside the modal
                    $('#editOfficeModal .modal-body').html('<p class="text-success">Office Name Updated Successfully!</p>');

                    // Optionally, close the modal after 2 seconds
                    setTimeout(function() {
                        $('#editOfficeModal').modal('hide');
                        location.reload(); // Reload the page after update
                    }, 2000);
                },
                error: function (error) {
                    // Handle error if necessary
                    alert('An error occurred while updating the office name.');
                }
            });
        });

        $('#editOfficeModal .btn-secondary').click(function () {
            $('#editOfficeModal').modal('hide'); // Close the modal manually
        });
    });

$(document).ready(function () {
    // Open the modal when "Delete" button is clicked
    $('.delete-btn').click(function () {
        var officeId = $(this).data('office-id');
        $('#deleteOfficeId').val(officeId); // Set officeId in the hidden input
        $('#deleteOfficeModal').modal('show'); // Show the delete confirmation modal
    });

    $('#deleteOfficeModal .btn-secondary').click(function () {
        $('#deleteOfficeModal').modal('hide'); // Close the modal manually
    });

});


$(document).ready(function() {
        $('#addOfficeForm').submit(function(e) {
            e.preventDefault(); 

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'error') {
                        $('#flash-message').html('<div class="alert alert-danger">' + response.message + '</div>');
                    } else if (response.status === 'success') {
                        $('#flash-message').html('<div class="alert alert-success">' + response.message + '</div>');
                        $('#addOfficeForm')[0].reset(); 
                        $('#addOfficeModal').modal('hide'); 
                        location.reload();
                    }
                }
            });
        });
    });
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

  <script>
    let table = new DataTable('#manageoffice', {
        "paging": true,          
        "pageLength": 5,         
        "lengthMenu": [5],       
        "info": true,            
        "lengthChange": false,
        "searching": false,   
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

