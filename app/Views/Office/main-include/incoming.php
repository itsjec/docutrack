<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="table-responsive pt-3">
                <table class="table table-striped project-orders-table">
                    <thead>
                        <tr>
                            <th>Tracking Number</th>
                            <th>Title</th>
                            <th>Sender</th>
                            <th>Status</th>
                            <th>Comment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= $document->tracking_number ?></td>
                                <td><?= $document->title ?></td>
                                <td><?= $senderDetails[$document->document_id]['sender_user'] ?> (<?= $senderDetails[$document->document_id]['sender_office'] ?>)</td>
                                <td><?= $document->status ?></td>
                                <td><?= $document->action ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Button to trigger the modal -->
                                        <a href="#" class="btn btn-success btn-sm btn-icon-text mr-3 receive-document-btn"
                                            data-title="<?= $document->title ?>"
                                            data-id="<?= $document->document_id ?>"
                                            data-toggle="modal"
                                            data-target="#receiveDocumentModal">
                                            Receive
                                            <i class="typcn typcn-edit btn-icon-append"></i>
                                        </a>
                                        <a href="#" class="btn btn-info btn-sm btn-icon-text mr-3">
                                            View
                                            <i class="typcn typcn-eye btn-icon-append"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm btn-icon-text">
                                            Delete
                                            <i class="typcn typcn-delete-outline btn-icon-append"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for receiving document confirmation -->
<div class="modal fade" id="receiveDocumentModal" tabindex="-1" role="dialog" aria-labelledby="receiveDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiveDocumentModalLabel">Receive Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Receive Document entitled <span id="documentTitle"></span>?
                <input type="hidden" id="documentId" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="receiveButton">Receive</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="errorModalLabel">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="errorMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.receive-document-btn').click(function() {
        var title = $(this).data('title');
        var id = $(this).data('id');
        $('#documentTitle').text(title);
        $('#documentId').val(id);
    });

    $('#receiveButton').click(function() {
        var id = $('#documentId').val();
        $.ajax({
            url: '<?= base_url("OfficeController/updateStatus"); ?>',
            method: 'POST',
            data: { document_id: id },
            dataType: 'json',
            success: function(response) {
                location.reload();
                $('#receiveDocumentModal').modal('hide');
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                $('#errorModal').modal('show');
                $('#errorMessage').text('An error occurred. Please try again later.');
            }
        });
    });
});


</script>