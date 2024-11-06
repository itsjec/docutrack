<ul class="nav">
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('dashboard') ?>">
            <i class="typcn typcn-home-outline menu-icon"></i>
            <span class="menu-title">Dashboard</span>
            <div class="badge badge-danger">new</div>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#profile-collapse" aria-expanded="false" aria-controls="profile-collapse">
            <i class="typcn typcn-user-outline menu-icon"></i>
            <span class="menu-title">Profile</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="profile-collapse">
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
        <a class="nav-link" data-toggle="collapse" href="#document-collapse" aria-expanded="false" aria-controls="document-collapse">
            <i class="typcn typcn-document-text menu-icon"></i>
            <span class="menu-title">Document</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="document-collapse">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageofficedocument') ?>">Manage Department/Office Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('managedocument') ?>">Manage Client Documents</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('viewtransactions') ?>">Transaction Log</a>
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
        <a class="nav-link" href="<?= site_url('activitytracker') ?>">
            <i class="typcn typcn-time menu-icon"></i> 
            <span class="menu-title">Activity Tracker</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#office-collapse" aria-expanded="false" aria-controls="office-collapse">
            <i class="typcn typcn-group-outline menu-icon"></i>
            <span class="menu-title">Office</span>
            <i class="menu-arrow"></i>
        </a>
        <div class="collapse" id="office-collapse">
            <ul class="nav flex-column sub-menu">
                <li class="nav-item">
                    <a class="nav-link" href="<?= site_url('manageoffice') ?>">Manage Department/Office</a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?= site_url('maintenance') ?>">
            <i class="typcn typcn-document-text menu-icon"></i>
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
