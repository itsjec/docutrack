<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Office Documents</title>
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
                        <h4 class="card-title">Manage Office Documents</h4>
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
                            <th>Status</th>
                            <th>Date of Document</th>
                            <th>Comment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td><?= $document['title'] ?></td>
                                <td><?= $document['tracking_number'] ?></td>
                                <td><?= $document['sender_office_name'] ?></td>
                                <td><?= $document['recipient_office_name'] ?></td>
                                <td><?= $document['status'] ?></td>
                                <td><?= $document['date_of_document'] ?></td>
                                <td><?= $document['action'] ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-info">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteDocumentModal" data-document-id="<?= $document['document_id'] ?>">
                                        <i class="mdi mdi-delete"></i> Delete
                                    </button>

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
                <br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="delete-btn">Confirm</button>
            </div>
        </div>
    </div>
</div>

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
            <?php if (session()->has('errors')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif ?>

                <?php if (session()->has('error')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session('error') ?>
                    </div>
                <?php endif ?>

                <!-- Your form here -->

                <form id="addDocumentForm" action="<?= site_url('documents/saveOffice') ?>" method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sender_office_id">Sender</label>
                            <select class="form-control" id="sender_office_id" name="sender_office_id" required>
                                <option value="" disabled selected>Select Sender</option>
                                <?php foreach ($officesDropdown as $office_id => $office_name): ?>
                                    <option value="<?= $office_id ?>"><?= $office_name ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <div class="form-group col-md-4">
                            <label for="recipient_office_id">Recipient</label>
                            <select class="form-control" id="recipient_office_id" name="recipient_office_id" required>
                                <option value="" disabled selected>Select Recipient</option>
                                <?php foreach ($officesDropdown as $office_id => $office_name): ?>
                                    <option value="<?= $office_id ?>"><?= $office_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="classification">Classification</label>
                            <select class="form-control" id="classification" name="classification" required>
                                <option value="" disabled selected>Select Classification</option>
                                <?php foreach ($classificationsDropdown as $classification): ?>
                                    <option value="<?= $classification ?>"><?= $classification ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="sub-classification">Sub-Classification</label>
                            <select id="sub-classification" name="sub_classification" class="form-control" required>
                                <option value="" disabled selected>Select Sub-Classification</option>
                                <?php foreach ($subClassificationsDropdown as $subClassification): ?>
                                    <option value="<?= $subClassification ?>"><?= $subClassification ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="date_of_document">Date of Document</label>
                            <input type="text" class="form-control" id="date_of_document" name="date_of_document" value="<?= date('Y-m-d') ?>" readonly>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="attachment">Attachment (PDF)</label>
                            <input type="file" class="form-control-file" id="attachment" name="attachment" accept=".pdf" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="action">Action</label>
                            <input type="text" class="form-control" id="action" name="action">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var documentId = $(this).data('document-id');

            console.log("doc id is: ", documentId);

            $('#confirmDeleteBtn').off('click').on('click', function() {
            console.log("it worked");
                $.ajax({
                    url: '<?= site_url('documents/deleteDocument') ?>',
                    type: 'POST',
                    data: { documentId: documentId },
                    success: function(response) {
                        if (response.success) {
                            console.log('Document deleted successfully');
                        } else {
                            console.error('Error deleting document:', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting document:', error);
                    }
                });

                $('#deleteConfirmationModal').modal('hide');
            });
        });
    });
</script>


<!-- jQuery -->

</script>

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

</body>
</html>
