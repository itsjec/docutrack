<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('index') ?>">
            <i class="typcn typcn-device-desktop menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-danger">new</div>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
            <i class="typcn typcn-document-text menu-icon"></i>
            <span class="menu-title">Document</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="ui-basic">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('pending') ?>">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('ongoing') ?>">On Process</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('completed') ?>">Completed</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('history') ?>">
            <i class="typcn typcn-device-desktop menu-icon"></i>
            <span class="menu-title">History</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#form-elements" aria-expanded="false" aria-controls="form-elements">
            <i class="typcn typcn-film menu-icon"></i>
            <span class="menu-title">Settings</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="form-elements">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageprofile') ?>">Manage Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('trash') ?>">Trash</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('/') ?>">
            <i class="typcn typcn-mortar-board menu-icon"></i>
            <span class="menu-title">Log Out</span>
        </a>
    </li>
</ul>
