<!-- Manage Documents Table -->
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Manage Documents</h4>
                        <p class="card-description">Track and update documents.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addDocumentModal">Add Document</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Tracking Number</th>
                                <th>Sender</th>
                                <th>Recipient</th>
                                <th>Current Office</th>
                                <th>Status</th>
                                <th>Comment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Static rows here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Document Modal -->
<div class="modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDocumentModalLabel">Add Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addDocumentForm" action="<?= site_url('documents/save') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="classification">Classification</label>
                        <select class="form-control" id="classification" name="classification" required>
                            <option value="" disabled selected>Select Classification</option>
                            <option value="Classification 1">Classification 1</option>
                            <option value="Classification 2">Classification 2</option>
                            <option value="Classification 3">Classification 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sub-classification">Sub-Classification</label>
                        <select id="sub-classification" name="sub_classification" class="form-control" required>
                            <option value="" disabled selected>Select Sub-Classification</option>
                            <option value="Sub-Classification 1">Sub-Classification 1</option>
                            <option value="Sub-Classification 2">Sub-Classification 2</option>
                            <option value="Sub-Classification 3">Sub-Classification 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_of_letter">Date of Letter</label>
                        <input type="text" class="form-control" id="date_of_letter" name="date_of_letter" value="YYYY-MM-DD" readonly>
                    </div>
                    <div class="form-group">
                        <label for="action">Action</label>
                        <input type="text" class="form-control" id="action" name="action">
                    </div>
                    <div class="form-group">
                        <label for="sender_office_id">Sender</label>
                        <select class="form-control" id="sender_office_id" name="sender_office_id">
                            <option value="Sender 1">Sender 1</option>
                            <option value="Sender 2">Sender 2</option>
                            <option value="Sender 3">Sender 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="receiver_office_id">Receiver</label>
                        <select class="form-control" id="receiver_office_id" name="receiver_office_id">
                            <option value="Receiver 1">Receiver 1</option>
                            <option value="Receiver 2">Receiver 2</option>
                            <option value="Receiver 3">Receiver 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attachment">Attachment (PDF)</label>
                        <input type="file" class="form-control-file" id="attachment" name="attachment" accept=".pdf" required>
                    </div>
                    <input type="hidden" name="current_office_id" value="">
                    <input type="hidden" name="status" value="pending">
                    <input type="hidden" name="tracking_number" value="">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Display validation errors -->
<?php if (session('validationErrors')) : ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach (session('validationErrors') as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Display file upload error -->
<?php if (session('error')) : ?>
    <div class="alert alert-danger"><?= session('error') ?></div>
<?php endif; ?>

<!-- Display success message -->
<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>


<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successModalLabel">Success!</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Document added successfully. 
                <h1><span id="trackingNumber"></span></h1>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="copyButton">Copy</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('copyButton').addEventListener('click', function() {
        var trackingNumber = document.getElementById('trackingNumber').innerText;
        navigator.clipboard.writeText(trackingNumber).then(function() {
            alert('Tracking number copied to clipboard!');
        }, function(err) {
            console.error('Failed to copy tracking number: ', err);
        });
    });

    <!-- Display success message -->
<?php if (session('success')) : ?>
    <div class="alert alert-success"><?= session('success') ?></div>
    <!-- Trigger the success modal -->
    <script>
        $(document).ready(function(){
            $('#successModal').modal('show');
            $('#trackingNumber').text('<?= session('trackingNumber') ?>');
        });
    </script>
<?php endif; ?>

</script>
