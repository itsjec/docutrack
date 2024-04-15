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
                                    <td><?= $document['sender_office_id'] ?></td>
                                    <td><?= $document['recipient_id'] ?></td>
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
                                        <a href="#" class="btn btn-sm btn-danger">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </a>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

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
