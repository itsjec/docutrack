<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('index') ?>">
            <i class="typcn typcn-chart-bar-outline menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-danger">new</div>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#addUser" aria-expanded="false" aria-controls="addUser">
            <i class="typcn typcn-user-outline menu-icon"></i>
            <span class="menu-title">Add User</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="addUser">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageofficeuser') ?>">Manage Department Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageofficeguest') ?>">Manage Client Users</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#addDocument" aria-expanded="false" aria-controls="addDocument">
            <i class="typcn typcn-document-add menu-icon"></i>
            <span class="menu-title">Add Document</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="addDocument">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('adddepartmentdocument') ?>">Add Department Document</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('addclientdocument') ?>">Add Client Document</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#documents" aria-expanded="false" aria-controls="documents">
            <i class="typcn typcn-folder-open menu-icon"></i> 
            <span class="menu-title">Document</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="documents">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('pending') ?>">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('received') ?>">Received</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('ongoing') ?>">On Process</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('completed') ?>">Completed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('allDocuments') ?>">All Documents</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('history') ?>">
            <i class="typcn typcn-arrow-back-outline menu-icon"></i>
            <span class="menu-title">History</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#settings" aria-expanded="false" aria-controls="settings">
            <i class="typcn typcn-cog-outline menu-icon"></i>
            <span class="menu-title">Settings</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="settings">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageprofile') ?>">Manage Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('trash') ?>">Trash</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('officemaintenance') ?>">Add Classification</a>
                </li>
            </ul>
        </div>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('/') ?>">
            <i class="typcn typcn-power-outline menu-icon"></i>
            <span class="menu-title">Log Out</span>
        </a>
    </li>
</ul>
