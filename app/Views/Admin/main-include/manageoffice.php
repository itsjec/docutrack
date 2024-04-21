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
                        <h4 class="card-title">Manage Offices</h4>
                        <p class="card-description">Track and update offices.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addOfficeModal">Add Office</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Office Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($offices as $office): ?>
                                <tr>
                                    <td><?= $office['office_name'] ?></td>
                                    <td>
                                        <a href="<?= base_url('edit/' . $office['office_id']) ?>" class="btn btn-sm btn-primary">
                                            <span class="mdi mdi-pencil"></span> Edit
                                        </a>
                                        <a href="<?= base_url('delete/' . $office['office_id']) ?>" class="btn btn-sm btn-danger">
                                            <span class="mdi mdi-delete"></span> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                </div>

                <!-- Modal -->
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
