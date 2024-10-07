<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('dashboard') ?>">
            <i class="typcn typcn-home-outline menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-danger">new</div>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="typcn typcn-user-outline menu-icon"></i>
            <span class="menu-title">Profile</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageuser') ?>">Manage Department/Client Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageguest') ?>">Manage Client Users</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
            <i class="typcn typcn-document-text menu-icon"></i>
            <span class="menu-title">Document</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageofficedocument') ?>">Manage Departmen/Office Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('managedocument') ?>">Manage Client Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('viewtransactions') ?>">View Transactions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('archived') ?>">Archived Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('all') ?>">All Documents</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#charts" aria-expanded="false" aria-controls="charts">
            <i class="typcn typcn-group-outline menu-icon"></i>
            <span class="menu-title">Office</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageoffice') ?>">Manage Department/Office</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('maintenance') ?>">
            <i class="typcn typcn-tools menu-icon"></i>
            <span class="menu-title">Manage Document Classifications</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('logout') ?>">
            <i class="typcn typcn-power-outline menu-icon"></i>
            <span class="menu-title">Log Out</span>
        </a>
    </li>
</ul>
