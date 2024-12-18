<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Documents</title>
    <!-- Material Design Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
</head>
<body>

<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Deleted Documents</h4>
                        <p class="card-description">Track and manage documents.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="archived">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Tracking Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $document): ?>
                                <tr>
                                    <td><?= $document->title ?></td>
                                    <td><?= $document->tracking_number ?></td>
                                    <td>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-document-id="<?= $document->document_id ?>">Delete
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
</div>

<div class="modal fade" id="deleteDocumentModal" tabindex="-1" role="dialog" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDocumentModalLabel">Delete Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the document?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelDeleteBtn">Cancel</button>
                <button type="button" class="btn btn-danger" id="delete-btn">Confirm</button>
            </div>
        </div>
    </div>
</div>



<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function () {
    var documentIdToDelete = null;

    // Open the confirmation modal and set the document ID to be deleted
    $('.delete-btn').click(function () {
        documentIdToDelete = $(this).data('document-id');
        $('#deleteDocumentModal').modal('show');
    });

    $('#delete-btn').click(function () {
        if (documentIdToDelete !== null) {
            $.ajax({
                url: '<?= site_url('documents/deleteDocument') ?>',  
                type: 'POST',
                data: {
                    documentId: documentIdToDelete
                },
                success: function (response) {
                    if (response.success) {
                        $('#deleteDocumentModal .modal-body').html('<p class="text-success">Document deleted successfully.</p>');
                        setTimeout(function () {
                            $('#deleteDocumentModal').modal('hide');
                            location.reload();  // Reload page after deletion
                        }, 2000);
                    } else {
                        $('#deleteDocumentModal .modal-body').html('<p class="text-danger">Failed to delete the document.</p>');
                    }
                },
                error: function () {
                    $('#deleteDocumentModal .modal-body').html('<p class="text-danger">An error occurred while deleting the document.</p>');
                }
            });
        }
    });

    $('#cancelDeleteBtn').click(function () {
        $('#deleteDocumentModal').modal('hide');
    });
});

</script>

</body>
</html>
