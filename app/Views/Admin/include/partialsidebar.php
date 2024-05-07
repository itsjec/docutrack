<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('dashboard') ?>">
            <i class="typcn typcn-device-desktop menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-danger">new</div>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="typcn typcn-document-text menu-icon"></i>
            <span class="menu-title">Profile</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageuser') ?>">Manage Office Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageguest') ?>">Manage Guest Users</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
            <i class="typcn typcn-film menu-icon"></i>
            <span class="menu-title">Document</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageofficedocument') ?>">Manage Office Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('managedocument') ?>">Manage Guest Documents</a>
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
            <i class="typcn typcn-chart-pie-outline menu-icon"></i>
            <span class="menu-title">Office</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="charts">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageoffice') ?>">Manage Office</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('maintenance') ?>">
            <i class="typcn typcn-device-desktop menu-icon"></i>
            <span class="menu-title">Maintenance</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('/') ?>">
            <i class="typcn typcn-mortar-board menu-icon"></i>
            <span class="menu-title">Log Out</span>
        </a>
    </li>
</ul>
