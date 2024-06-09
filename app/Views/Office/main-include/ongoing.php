<div class="content-wrapper">
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
                                <td> <?php if ($document->sender_office_id === null): ?>
                <?= $senderDetails[$document->document_id]['sender_user'] ?>
            <?php else: ?>
                <?= $senderDetails[$document->document_id]['sender_office'] ?>
            <?php endif; ?></td>
                                <td><?= $document->status ?></td>
                                <td><?= $document->action ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                    <button type="button" class="btn btn-success btn-sm btn-icon-text mr-3 receive-btn" data-toggle="modal" data-target="#receiveDocumentModal" data-document-id="<?= $document->document_id ?>" data-document-title="<?= $document->title ?>" data-tracking-number="<?= $document->tracking_number ?>">
                                        <span class="btn-icon-prepend">
                                            <i class="typcn typcn-edit"></i>
                                        </span>
                                        Mark as Done
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary view-btn"
                                        data-toggle="modal" data-target="#viewDocumentModal"
                                        data-documentid="<?= esc($document->document_id) ?>"
                                        data-title="<?= esc($document->title) ?>"
                                        data-tracking-number="<?= esc($document->tracking_number) ?>">View
                                        <i class="typcn typcn-eye btn-icon-append"></i>
                                    </button>
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
</div>

<!-- Modal -->
<div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Document Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <div id="qrCodeContainer"></div>
                        <h4><strong><span id="view-tracking-number"></span></strong></h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Update Status Modal -->
<div class="modal fade" id="receiveDocumentModal" tabindex="-1" role="dialog" aria-labelledby="receiveDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiveDocumentModalLabel">Mark as Done</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you this document entitled <span id="documentTitle"></span> is completed?
                <br>
                Tracking Number: <span id="trackingNumber"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmReceiveBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

