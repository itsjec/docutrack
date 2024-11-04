<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Department Documents</title>
    <!-- Material Design Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <style>

  .status-badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 12px; /* Oval shape */
    text-align: center;
    background-color: purple;
    text-transform: lowercase;
    color: #fff; /* Default font color */
  }

  .status-pending {
    background-color: #ffc107; /* Yellow */
    color: #212529; /* Dark text for better readability */
  }

  .status-received {
    background-color: #17a2b8; /* Teal */
    color: #fff; /* White text for better readability */
  }

  .status-on-process {
    background-color: #007bff; /* Blue */
    color: #fff; /* White text for better readability */
  }

  .status-completed {
    background-color: #28a745; /* Green */
    color: #fff; /* White text for better readability */
  }

  .status-deleted {
    background-color: #dc3545; /* Red */
    color: #fff; /* White text for better readability */
  }

  
  .custom-select {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #C9C8C8;
            background-color: #FFFFFF;
            padding: 7px 12px;
            border: 1px solid #C9C8C8;
            border-radius: 4px;
        }

        /* Style for Select2 dropdown */
        .select2-container--default .select2-selection--single {
            background-color: #FFFFFF;
            border: 1px solid #C9C8C8;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            padding: 7px 12px;
            height: auto;
        }

        /* Adjust padding for Select2 selection */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
        }

        /* Style for the dropdown options */
        .select2-container--default .select2-results__option {
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            background-color: #FFFFFF;
            padding: 7px 12px;
        }

        /* Change background on hover */
        .select2-container--default .select2-results__option--highlighted {
            background-color: #C9C8C8;
            color: #FFFFFF;
        }
</style>
</head>
<body>
<div class="content-wrapper">
<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Manage Department Documents</h4>
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
                            <th>Version No.</th>
                            <th>Title</th>
                            <th>Tracking Number</th>
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
                                <td><?= $document['version_number'] ?></td>
                                <td><?= $document['title'] ?></td>
                                <td><?= $document['tracking_number'] ?></td>
                                <td><?= $document['recipient_office_name'] ?></td>
                                <td><span class="status-badge status-<?= $document['status'] ?>"><?= ucfirst($document['status']) ?></span></td>
                                <td><?= date('F d, Y', strtotime($document['date_of_document'])) ?></td>
                                <td><?= $document['action'] ?></td>
                                <td>
                            <button type="button" class="btn btn-sm btn-primary edit-btn"
                                    data-toggle="modal" data-target="#editDocumentModal"
                                    data-documentid="<?= $document['document_id'] ?>"
                                    data-title="<?= $document['title'] ?>"
                                    data-sender-office-id="<?= $document['sender_office_id'] ?>"
                                    data-recipient-office-id="<?= $document['recipient_id'] ?>"
                                    data-classification="<?= $document['classification'] ?>"
                                    data-sub-classification="<?= $document['sub_classification'] ?>"
                                    data-date-of-document="<?= $document['date_of_document'] ?>"
                                    data-action="<?= $document['action'] ?>"
                                    data-description="<?= $document['description'] ?>">
                                <i class="mdi mdi-pencil"></i> Edit
                            </button>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-toggle="modal" data-target="#deleteDocumentModal" data-document-id="<?= $document['document_id'] ?>">
                                    <i class="mdi mdi-delete"></i> Delete
                                </button>
                                <button type="button" class="btn btn-sm btn-info view-btn" 
                                        data-document-url="<?= base_url($document['attachment']) ?>"> 
                                    <i class="mdi mdi-eye"></i> View 
                                </button>
                            </td>
                            </tr>
                            <tr class="pdf-row" style="display: none;">
                                <td colspan="100%">
                                    <iframe class="pdf-viewer" style="width: 100%; height: 500px;" src=""></iframe>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Document Versions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="documentVersionsBody">
                <!-- Versions will be dynamically added here -->
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editDocumentModal" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDocumentModalLabel">Edit Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Your form here -->
                <form id="editDocumentForm" action="<?= site_url('documents/updateDeptDocument') ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" id="editDocumentId" name="id">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="editTitle">Title</label>
                <input type="text" class="form-control" id="editTitle" name="title" placeholder="Enter title" required>
            </div>
            <div class="form-group col-md-4">
                <label for="editSenderOfficeId">Sender</label>
                <select class="form-control" id="editSenderOfficeId" name="sender_office_id" required>
                    <?php foreach ($officesDropdown as $office_id => $office_name): ?>
                        <option value="<?= $office_id ?>"><?= $office_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="editRecipientOfficeId">Recipient</label>
                <select class="form-control" id="editRecipientOfficeId" name="recipient_office_id" required>
                    <?php foreach ($officesDropdown as $office_id => $office_name): ?>
                        <option value="<?= $office_id ?>"><?= $office_name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
        <div class="form-group col-md-6">
            <label for="editAttachment">Attachment (PDF)</label>
            <input type="file" class="form-control-file" id="editAttachment" name="attachment" accept=".pdf">
            <?php if (!empty($document['attachment'])): ?>
                <p>Current Attachment: <a href="<?= base_url('uploads/' . $document['attachment']) ?>" target="_blank"><?= $document['attachment'] ?></a></p>
            <?php endif; ?>
        </div>
            <div class="form-group col-md-6">
                <label for="editDateOfDocument">Date of Document</label>
                <input type="text" class="form-control" id="editDateOfDocument" name="date_of_document" value="<?= date('Y-m-d') ?>" readonly>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="editAction">Action</label>
                <input type="text" class="form-control" id="editAction" name="action">
            </div>
            <div class="form-group col-md-6">
                <label for="editDescription">Description</label>
                <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
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

<div class=" modal fade" id="addDocumentModal" tabindex="-1" role="dialog" aria-labelledby="addDocumentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentModalLabel">Add Document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <?php if (session()->has('error')): ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session('error') ?>
                        </div>
                    <?php endif ?>

                    <form id="addDocumentForm" action="<?= site_url('documents/saveDepartment') ?>" method="post"
                        enctype="multipart/form-data">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group col-12 d-flex flex-column">
                                <label for="sender_office_id">Sender</label>
                                <select name="sender_office_id" class="form-custom-select searchable-dropdown" required>
                                    <option></option> <!-- Placeholder option -->
                                </select>
                            </div>

                            <div class="form-group col-12 d-flex flex-column">
                                <label for="recipient_office_id">Recipient</label>
                                <select class="form-custom-select searchable-dropdown"" name=" recipient_office_id"
                                    required>
                                    <option></option> <!-- Placeholder option -->
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-12 col-md-6">
                                    <label for="classification">Classification</label>
                                    <select class="form-control" id="classification" name="classification" required>
                                        <option value="" disabled selected>Select Classification</option>
                                        <?php foreach ($classificationsDropdown as $classification): ?>
                                            <option value="<?= $classification ?>"><?= $classification ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="sub-classification">Sub-Classification</label>
                                    <select id="sub-classification" name="sub_classification" class="form-control"
                                        required>
                                        <option value="" disabled selected>Select Sub-Classification</option>
                                        <?php foreach ($subClassificationsDropdown as $subClassification): ?>
                                            <option value="<?= $subClassification ?>"><?= $subClassification ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="action">Action</label>
                                    <input type="text" class="form-control" id="action" name="action">
                                </div>
                                <div class="form-group col-12 col-md-6">
                                    <label for="date_of_document">Date of Document</label>
                                    <input type="text" class="form-control" id="date_of_document"
                                        name="date_of_document" value="<?= date('Y-m-d') ?>" readonly>
                                </div>
                                <div class="form-group col-12">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description"
                                        rows="3"></textarea>
                                </div>
                                <div class="form-group col-12">
                                    <label for="attachment">Attachment (PDF)</label>
                                    <input type="file" class="form-control-file" id="attachment" name="attachment"
                                        accept=".pdf" required>
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


    <script>
        $(document).ready(function () {
            // Initialize Select2 on all elements with the class 'searchable-dropdown'
            $('.searchable-dropdown').select2({
                placeholder: "Select an option",
                allowClear: true,
                width: 'resolve',
                dropdownParent: $('#addDocumentModal')
            });

            // Fetch office list from the server and populate each searchable-dropdown
            $.ajax({
                url: '/officelist', // Replace with your API endpoint
                method: 'GET',
                success: function (data) {
                    // Populate each dropdown
                    $('.searchable-dropdown').each(function () {
                        const $dropdown = $(this);

                        // Add options to each dropdown
                        data.forEach(function (office) {
                            $dropdown.append(new Option(office.office_name, office.office_id));
                        });


                    });
                },
                error: function (err) {
                    console.log(err);
                }
            });
        });
    </script>



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

<script>
    $(document).ready(function() {
        $('.delete-btn').click(function() {
            var documentId = $(this).data('document-id');

            console.log("doc id is: ", documentId);

            $('#confirmDeleteBtn').off('click').on('click', function() {
            console.log("it worked");
                $.ajax({
                    url: '<?= site_url('documents/archiveDocument') ?>',
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




<script>
$(document).ready(function() {
    $('.edit-btn').on('click', function() {
        var documentId = $(this).data('documentid');
        var title = $(this).data('title');
        var senderOfficeId = $(this).data('sender-office-id');
        var recipientOfficeId = $(this).data('recipient-office-id');
        var classification = $(this).data('classification');
        var subClassification = $(this).data('sub-classification');
        var dateOfDocument = $(this).data('date-of-document');
        var action = $(this).data('action');
        var description = $(this).data('description');

        $('#editDocumentId').val(documentId);
        $('#editTitle').val(title);
        $('#editSenderOfficeId').val(senderOfficeId);
        $('#editRecipientOfficeId').val(recipientOfficeId);
        $('#editDateOfDocument').val(dateOfDocument);
        $('#editAction').val(action);
        $('#editDescription').val(description);

        // Set the selected option for classification dropdown
        $('#editClassification option').each(function() {
            if ($(this).val() == classification) {
                $(this).prop('selected', true);
            } else {
                $(this).prop('selected', false);
            }
        });

        // Set the selected option for sub_classification dropdown
        $('#editSubClassification option').each(function() {
            if ($(this).val() == subClassification) {
                $(this).prop('selected', true);
            } else {
                $(this).prop('selected', false);
            }
        });

        $('#editDocumentModal').modal('show');
    });

    $('#editDocumentForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            url: 'documents/updateDocument',
            method: 'post',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Handle success response
                $('#editDocumentModal').modal('hide');
                // Reload or update the document list
                window.location.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
        const viewButtons = document.querySelectorAll('.view-btn');

        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                const pdfUrl = this.getAttribute('data-document-url');
                const row = this.closest('tr'); 
                const pdfRow = row.nextElementSibling; 
                const iframe = pdfRow.querySelector('.pdf-viewer'); 

                iframe.src = pdfUrl;

                if (pdfRow.style.display === 'none' || pdfRow.style.display === '') {
                    pdfRow.style.display = 'table-row';
                } else {
                    pdfRow.style.display = 'none';
                }
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
