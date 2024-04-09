<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-8">
                        <h4 class="card-title">Manage Offices</h4>
                        <p class="card-description">Track and update offices.</p>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addClassificationModal">Add Classification</button>
                    </div>
                    <div class="col-md-6">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addSubClassificationModal">Add Sub-Classification</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
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
                                            <a href="<?= base_url('edit/' . $classification['classification_id']) ?>" class="btn btn-sm btn-primary">Edit</a>
                                            <a href="<?= base_url('delete/' . $classification['classification_id']) ?>" class="btn btn-sm btn-danger">Delete</a>
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
                <form id="addClassificationForm" action="<?= site_url('classifications/save') ?>" method="post">
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
                <form id="addSubClassificationForm" action="<?= site_url('sub-classifications/save') ?>" method="post">
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

