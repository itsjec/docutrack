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
                            <th>Current Office</th>
                            <th>Status</th>
                            <th>Date Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= $document->tracking_number ?></td>
                                <td><?= $document->title ?></td>
                                <td>
                                    <?php if ($document->sender_office_id !== null): ?>
                                        <?= $senderDetails[$document->document_id]['sender_office'] ?>
                                    <?php else: ?>
                                        <?= $senderDetails[$document->document_id]['sender_user'] ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $document->recipient_office_name ?></td>
                                <td><?= $document->history_status ?></td>
                                <td><?= date('F j, Y', strtotime($document->date_completed)) ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
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
