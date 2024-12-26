<div class="container-fluid">

    <div id="two-column-menu">
    </div>
    <ul class="navbar-nav" id="navbar-nav">
        <li class="menu-title"><span data-key="t-menu">Menu</span></li>
        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarDashboards" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="sidebarDashboards">
                <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboards</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarDashboards">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="dashboard-analytics.html" class="nav-link" data-key="t-analytics">
                            Analytics </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- end Dashboard Menu -->

        <li class="menu-title"><i class="ri-more-fill"></i> <span data-key="t-pages">Pages</span>
        </li>

        <li class="nav-item">
            <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="sidebarAuth">
                <i class="ri-account-circle-line"></i> <span data-key="t-authentication">Authentication</span>
            </a>
            <div class="collapse menu-dropdown" id="sidebarAuth">
                <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                        <a href="#sidebarSignIn" class="nav-link" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarSignIn" data-key="t-signin"> Sign In
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarSignIn">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="auth-signin-basic.html" class="nav-link" data-key="t-basic"> Basic
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="auth-signin-cover.html" class="nav-link" data-key="t-cover"> Cover
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</div>
