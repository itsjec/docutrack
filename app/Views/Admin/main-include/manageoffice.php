<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
                        <h4 class="card-title">Manage Departments</h4>
                        <p class="card-description">Track and update departments.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addOfficeModal">Add New Department</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="manageoffice">
                        <thead>
                            <tr>
                                <th>Department Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offices as $office): ?>
                                <tr>
                                    <td><?= $office['office_name'] ?></td>
                                    <td>
                                    <a href="#" class="btn btn-sm btn-primary edit-btn" data-office-id="<?= $office['office_id'] ?>" data-office-name="<?= $office['office_name'] ?>">
                                        <span class="mdi mdi-pencil"></span> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger delete-btn" data-office-id="<?= $office['office_id'] ?>">
                                        <span class="mdi mdi-delete"></span> Delete
                                    </a>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>

                <!-- Modal: Delete Office -->
                <div class="modal fade" id="deleteOfficeModal" tabindex="-1" role="dialog" aria-labelledby="deleteOfficeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteOfficeModalLabel">Confirm Delete</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this office?</p>
                                <form id="deleteOfficeForm" action="<?= base_url('office/updateStatus') ?>" method="post">
                                    <input type="hidden" id="deleteOfficeId" name="officeId">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                                        <button type="submit" class="btn btn-danger">Yes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



        <!-- Modal: Edit Office -->
        <div class="modal fade" id="editOfficeModal" tabindex="-1" role="dialog" aria-labelledby="editOfficeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOfficeModalLabel">Edit Office Name</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editOfficeForm" action="<?= base_url('updateOfficeName') ?>" method="post">
                            <input type="hidden" id="editOfficeId" name="officeId">
                            <div class="form-group">
                                <label for="editOfficeName">Office Name</label>
                                <input type="text" class="form-control" id="editOfficeName" name="officeName" placeholder="Enter office name" required>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close
                        </button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


                <div class="modal fade" id="addOfficeModal" tabindex="-1" role="dialog" aria-labelledby="addOfficeModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addOfficeModalLabel">Add Office</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Flashdata Messages -->
                                <div id="flash-message"></div>

                                <!-- Add Office Form -->
                                <form id="addOfficeForm" action="<?= site_url('offices/save') ?>" method="post">
                                    <div class="form-group">
                                        <label for="officeName">Office Name</label>
                                        <input type="text" class="form-control" id="officeName" name="officeName" placeholder="Enter office name" required>
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

            </div>
        </div>
    </div>
</div>

</body>
</html>
