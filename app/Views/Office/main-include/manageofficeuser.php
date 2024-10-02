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
                        <h4 class="card-title">Manage Department Users</h4>
                        <p class="card-description">Track and update users.</p>
                    </div>
                    <div class="col-4 text-right">
                        <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add Department User</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Image</th>
                                <th>Department Name</th>
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
                                    <a href="#" class="btn btn-sm <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'btn-success' : 'btn-danger' ?> deactivate-btn" 
                                        data-toggle="modal" 
                                        data-target="#<?= isset($user['status']) && $user['status'] == 'deactivate' ? 'activateUserModal_'.$user['user_id'] : 'deactivateUserModal_'.$user['user_id']?>"
                                        data-userid="<?= $user['user_id'] ?>">
                                            <i class="mdi <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'mdi-check' : 'mdi-close' ?>"></i>
                                            <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'Activate' : 'Deactivate' ?>
                                    </a>
                                </td>
                            </tr>

                            <!-- Activate User Modal -->
                            <div class="modal fade" id="activateUserModal_<?= $user['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="activateUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="activateUserModalLabel">Activate User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to activate this user?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-success" id="confirmActivate_<?= $user['user_id'] ?>" data-userid="<?= $user['user_id'] ?>">Activate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deactivate User Modal -->
                            <div class="modal fade" id="deactivateUserModal_<?= $user['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="deactivateUserModalLabel" aria-hidden="true">
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
                                            <button type="button" class="btn btn-danger" id="confirmDeactivate_<?= $user['user_id'] ?>" data-userid="<?= $user['user_id'] ?>">Deactivate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                <form id="editUserForm" action="<?= site_url('officeusers/update') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="editUserId" name="userId">

                    <div class="form-group">
                        <label for="editOfficeId">Department</label>
                        <select class="form-control" id="editOfficeId" name="officeId" disabled>
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['office_id'] ?>" <?= ($office['office_id'] == session('office_id')) ? 'selected' : '' ?>>
                                    <?= $office['office_name'] ?>
                                </option>
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

                    <div class="form-group">
                        <label for="editProfilePic">Profile Picture</label>
                        <input type="file" class="form-control-file" id="editProfilePic" name="profilePic" accept="image/*" onchange="previewImage(event)">
                        <img id="profilePicPreview" src="#" alt="Profile Picture Preview" style="display:none; margin-top: 10px; max-width: 100%;">
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
                <h5 class="modal-title" id="addUserModalLabel">Add Department User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal-message" class="mb-3"></div> <!-- Error message will be displayed here -->
                <form id="addUserForm" action="<?= site_url('users/save') ?>" method="post">
                    <!-- Office Dropdown -->
                    <div class="form-group">
                        <label for="officeId">Department</label>
                        <select class="form-control" id="officeId" name="officeId">
                            <?php foreach ($offices as $office): ?>
                                <option value="<?= $office['office_id'] ?>"><?= $office['office_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- First Name and Last Name in Two Columns -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Username and Password in Two Columns -->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                            </div>
                            <div class="col-md-6">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                <meter max="4" id="password-strength-meter"></meter>
                                <p id="password-strength-text"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
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

    $(document).ready(function() {
        $('#addUserForm').on('submit', function(event) {
            event.preventDefault(); // Prevent the form from submitting the traditional way
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        $('#modal-message').html('<div class="alert alert-danger">' + response.error + '</div>');
                    } else if (response.success) {
                        $('#modal-message').html('<div class="alert alert-success">' + response.success + '</div>');
                        setTimeout(function() {
                            $('#addUserModal').modal('hide');
                            location.reload(); // Reload the page
                        }, 2000);
                    }
                },
                error: function() {
                    $('#modal-message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
            });
        });
    });

    $(document).ready(function() {
        $('body').on('click', '[id^=confirmActivate_]', function() {
            var userId = $(this).data('userid');

            $.ajax({
                url: '<?= site_url('user/activate') ?>',
                type: 'POST',
                data: { user_id: userId },
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to activate user.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });
    });

    $(document).ready(function() {
        $('body').on('click', '[id^=confirmDeactivate_]', function() {
            var userId = $(this).data('userid');

            $.ajax({
                url: '<?= site_url('user/deactivate') ?>',
                type: 'POST',
                data: { user_id: userId },
                success: function(response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to deactivate user.');
                    }
                },
                error: function() {
                    alert('An error occurred.');
                }
            });
        });
    });

    function previewImage(event) {
    const preview = document.getElementById('profilePicPreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = '#';
        preview.style.display = 'none';
    }
}
</script>

</body>
</html>
