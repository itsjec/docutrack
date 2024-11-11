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
                        <h4 class="card-title">Manage Document Classifications</h4>
                        <p class="card-description">Update document classification</p>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addClassificationModal">Add Classification</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addSubClassificationModal">Add Sub-Classification</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="officemaintenance">
                        <thead>
                            <tr>
                                <th>Classification</th>
                                <th>Subclassification</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($classifications as $classification): ?>
                                <?php if (!empty($classification['sub_classification'])): ?>
                                    <tr>
                                        <td><?= $classification['classification_name'] ?></td>
                                        <td><?= $classification['sub_classification'] ?></td>
                                        <td>
                                        <a href="#" class="btn btn-sm btn-primary edit-subclassification-btn"
                                            data-subclassification-id="<?= $classification['classification_id'] ?>"
                                            data-classification="<?= $classification['classification_name'] ?>"
                                            data-subclassification="<?= $classification['sub_classification'] ?>">
                                            <span class="mdi mdi-pencil"></span> Edit
                                        </a>
                                            <a href="#" class="btn btn-sm btn-danger delete-btn" data-office-id="<?= $classification['classification_id'] ?>">
                                                <span class="mdi mdi-delete"></span> Delete
                                            </a>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

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
                <p>Are you sure you want to delete this document classification?</p>
                <form id="deleteOfficeForm" action="<?= base_url('office/updateDepartmentClassification') ?>" method="post">
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

<!-- Edit Subclassification Modal -->
<div class="modal fade" id="editSubClassificationModal" tabindex="-1" role="dialog" aria-labelledby="editSubClassificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubClassificationModalLabel">Edit Subclassification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSubClassificationForm" action="<?= site_url('classifications/update') ?>" method="post">
                    <input type="hidden" id="editClassificationId" name="classificationId">
                    <div class="form-group">
                        <label for="editClassification">Classification:</label>
                        <select class="form-control" id="classification" name="classification" required>
                                <option value="" disabled selected>Select Classification</option>
                                <?php foreach ($classificationsDropdown as $classification): ?>
                                    <option value="<?= $classification ?>"><?= $classification ?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="editSubclassification">Subclassification Name</label>
                        <input type="text" class="form-control" id="editSubclassification" name="subclassificationName" placeholder="Enter subclassification name" required>
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




<!-- Add Classification Modal -->
<div class="modal fade" id="addClassificationModal" tabindex="-1" role="dialog" aria-labelledby="addClassificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClassificationModalLabel">Add Classification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addClassificationForm" action="<?= site_url('docuclassifications/save') ?>" method="post">
                    <div class="form-group">
                        <label for="classificationName">Classification Name</label>
                        <input type="text" class="form-control" id="classificationName" name="classificationName" placeholder="Enter classification name" required>
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

<!-- Add Subclassification Modal -->
<div class="modal fade" id="addSubClassificationModal" tabindex="-1" role="dialog" aria-labelledby="addSubClassificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubClassificationModalLabel">Add Subclassification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addSubClassificationForm" action="<?= site_url('docusub-classifications/save') ?>" method="post">
                    <div class="form-group">
                        <label for="classification">Classification:</label>
                        <select class="form-control" id="classification" name="classification" required>
                                <option value="" disabled selected>Select Classification</option>
                                <?php foreach ($classificationsDropdown as $classification): ?>
                                    <option value="<?= $classification ?>"><?= $classification ?></option>
                                <?php endforeach; ?>
                            </select>
                    </div>
                    <div class="form-group">
                        <label for="subclassification">Subclassification Name</label>
                        <input type="text" class="form-control" id="subclassification" name="subclassification" placeholder="Enter subclassification name" required>
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

</body>
</html>
