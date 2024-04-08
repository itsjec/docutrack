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
                                        <a href="#" class="btn btn-success btn-sm btn-icon-text mr-3">
                                            Edit
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
