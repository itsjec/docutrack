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
                        <h4 class="card-title">Manage Office Users</h4>
                        <p class="card-description">Track and update users.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add User</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Image</th>
                                <th>Office Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['username'] ?></td>
                                <td><img src="<?= $user['picture_path'] ?>" alt="User Image" width="50"></td>
                                <td><?= isset($user['office_name']) ? $user['office_name'] : 'N/A' ?></td>
                                <td>
                                <a href="#editUserModal" class="btn btn-sm btn-primary edit-btn"
                                        data-toggle="modal"
                                        data-user-id="<?= $user['user_id'] ?>"
                                        data-office-id="<?= $user['office_id'] ?>"
                                        data-email="<?= $user['email'] ?>"
                                        data-username="<?= $user['username'] ?>"
                                        data-password="<?= $user['password'] ?>">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm btn-danger deactivate-btn" 
                                        data-toggle="modal" 
                                        data-target="#deactivateUserModal" 
                                        data-userid="<?= $user['user_id'] ?>">
                                        <i class="mdi mdi-close"></i> Deactivate
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


<!-- Deactivate User Modal -->
<div class="modal fade" id="deactivateUserModal" tabindex="-1" role="dialog" aria-labelledby="deactivateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deactivateUserModalLabel">Deactivate User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to deactivate this user?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeactivate">Deactivate</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this user?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a id="deleteUserButton" href="<?= base_url('delete/' . $user['user_id']) ?>" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editUserForm" action="<?= site_url('users/update') ?>" method="post">
                    <input type="hidden" id="editUserId" name="userId">
                    <div class="form-group">
                        <label for="editOfficeId">Office</label>
                        <select class="form-control" id="editOfficeId" name="officeId">
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editUsername">Username</label>
                        <input type="text" class="form-control" id="editUsername" name="username" placeholder="Enter username" required>
                    </div>
                    <div class="form-group">
                        <label for="editPassword">Password</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Enter new password">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add User Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" action="<?= site_url('users/save') ?>" method="post">
                    <div class="form-group">
                        <label for="officeId">Office</label>
                        <select class="form-control" id="officeId" name="officeId">
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                            </div>
                            <div class="col">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                <meter max="4" id="password-strength-meter"></meter>
                                <p id="password-strength-text"></p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>

<script>
    document.getElementById('password').addEventListener('input', function () {
        var password = document.getElementById('password').value;
        var result = zxcvbn(password);
        var meter = document.getElementById('password-strength-meter');
        var text = document.getElementById('password-strength-text');

        meter.value = result.score;
        
        var strength = "";
        switch (result.score) {
            case 0:
                strength = "Very Weak";
                meter.style.color = 'red';
                break;
            case 1:
                strength = "Weak";
                meter.style.color = 'orange';
                break;
            case 2:
                strength = "Fair";
                meter.style.color = 'yellow';
                break;
            case 3:
                strength = "Good";
                meter.style.color = 'lightgreen';
                break;
            case 4:
                strength = "Strong";
                meter.style.color = 'green';
                break;
        }

        text.textContent = "Password Strength: " + strength;
    });
</script>

</body>
</html>
