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
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">
                        <i class="mdi mdi-account-plus" style="font-size: 20px; margin-right: 8px;"></i> Add Department User
                    </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id ="myUserTable">
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
                                <td><?= esc($user['username']) ?></td>
                                <td>
                                    <?php if (!empty($user['picture_path'])): ?>
                                        <img src="<?= esc($user['picture_path']) ?>" alt="User Image" width="100" height="auto">
                                    <?php else: ?>
                                        <img src="<?= base_url('path/to/default/image.jpg') ?>" alt="Default User Image" width="100" height="auto"> <!-- Correct path to default image -->
                                    <?php endif; ?>
                                </td>
                                <td><?= isset($user['office_name']) ? esc($user['office_name']) : 'N/A' ?></td>
                                <td>
                                    <a href="#editUserModal" class="btn btn-sm btn-primary edit-btn"
                                        data-toggle="modal"
                                        data-user-id="<?= esc($user['user_id']) ?>"
                                        data-office-id="<?= esc($user['office_id']) ?>"
                                        data-email="<?= esc($user['email']) ?>"
                                        data-username="<?= esc($user['username']) ?>"
                                        data-password="<?= esc($user['password']) ?>">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <a href="#" class="btn btn-sm <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'btn-success' : 'btn-danger' ?> deactivate-btn" 
                                        data-toggle="modal" 
                                        data-target="#<?= isset($user['status']) && $user['status'] == 'deactivate' ? 'activateUserModal_' . esc($user['user_id']) : 'deactivateUserModal_' . esc($user['user_id']) ?>"
                                        data-userid="<?= esc($user['user_id']) ?>">
                                        <i class="mdi <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'mdi-check' : 'mdi-close' ?>"></i>
                                        <?= isset($user['status']) && $user['status'] == 'deactivate' ? 'Activate' : 'Deactivate' ?>
                                    </a>
                                </td>
                            </tr>

                            <!-- Activate User Modal -->
                            <div class="modal fade" id="activateUserModal_<?= esc($user['user_id']) ?>" tabindex="-1" role="dialog" aria-labelledby="activateUserModalLabel" aria-hidden="true">
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
                                            <button type="button" class="btn btn-success" id="confirmActivate_<?= esc($user['user_id']) ?>" data-userid="<?= esc($user['user_id']) ?>">Activate</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deactivate User Modal -->
                            <div class="modal fade" id="deactivateUserModal_<?= esc($user['user_id']) ?>" tabindex="-1" role="dialog" aria-labelledby="deactivateUserModalLabel" aria-hidden="true">
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
                                            <button type="button" class="btn btn-danger" id="confirmDeactivate_<?= esc($user['user_id']) ?>" data-userid="<?= esc($user['user_id']) ?>">Deactivate</button>
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


<!-- Edit User Modal -->
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
                <form id="editUserForm" action="<?= site_url('users/update') ?>" method="post" enctype="multipart/form-data">
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
                    
                    <!-- Profile Picture Upload Field -->
                    <div class="form-group">
                        <label for="editProfilePicture">Profile Picture</label>
                        <input type="file" class="form-control-file" id="editProfilePicture" name="profilePicture" accept="image/*" onchange="previewImage(event)">
                        <img id="imagePreview" src="#" alt="Image Preview" style="display:none; margin-top:10px; max-width:100%;"/>
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
                                <label for="firstName">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter first name" required>
                            </div>
                            <div class="col">
                                <label for="lastName">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter last name" required>
                            </div>
                        </div>
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

    $(document).ready(function() {
        $('#addUserForm').on('submit', function(event) {
            event.preventDefault();
            
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
                            location.reload(); 
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

    $(document).ready(function () {
        $('.edit-btn').click(function () {
            var userId = $(this).data('user-id');
            var officeId = $(this).data('office-id');
            var username = $(this).data('username');
            var email = $(this).data('email');
            var profilePicture = $(this).data('profile-picture');

            $('#editUserId').val(userId);
            $('#editOfficeId').val(officeId);
            $('#editUsername').val(username);
            $('#editEmail').val(email); 
            if (profilePicture) {
                $('#imagePreview').attr('src', profilePicture).show(); 
            } else {
                $('#imagePreview').hide(); 
            }

            $('#editUserModal').modal('show');
        });

        $('#editUserModal').on('hidden.bs.modal', function () {
            $('.modal-backdrop').remove();  
        });
    });


    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function () {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block'; 
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

</script>
</body>
</html>
