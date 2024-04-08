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
                                    <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3 edit-btn" data-toggle="modal" data-target="#updateStatusModal" data-document-id="<?= $document->document_id ?>">
                                            Edit
                                            <i class="typcn typcn-edit btn-icon-append"></i>
                                        </button>
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

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusModalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to change the status?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmUpdateBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

