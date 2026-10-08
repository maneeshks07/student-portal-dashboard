<?php
$liDashboard    = "";
$liStudents     = "";
$liLogout       = "";

if ($activePage == "dashboard") 
    {
        $liDashboard = " active_mnu";
    }

if ($activePage == "students") 
    {
        $liStudents = " active_mnu";
    }

if ($activePage == "logout") 
    {
        $liLogout = " active_mnu";
    }
?>
<!--mobile header-->
<div class="mobile-header d-flex align-items-center justify-content-between">
    <div class="mob_logo"><img alt="" class="w-6" src="images/logo.svg"></div>

    <div class="mob_menu">
        <input type="checkbox" id="menu-toggle-checkbox" class="menu-toggle-checkbox">
        <label for="menu-toggle-checkbox" id="menu-toggle" class="menu-toggle-label">
            <i class="bi bi-list text-white" style="font-size: 28px;"></i>
        </label>
        <!-- Side menu -->
        <div id="side-menu" class="side-menu">
            <label for="menu-toggle-checkbox" id="close-menu" class="close-btn">&times;</label>

            <div class="navigation_sec mt-4">
                <ul>
                    <li class="<?php echo $liDashboard; ?>">
                        <a href="dashboard.php">
                            <i class="fa-solid fa-gauge-high"></i>
                            <span class="hd_tb">Dashboard</span>
                        </a>
                    </li>
                    <li class="<?php echo $liStudents; ?>">
                        <a href="manage_students.php">
                            <i class="fa-solid fa-users"></i>
                            <span class="hd_tb">Student List</span>
                        </a>
                    </li>
                    <li class="<?php echo $liLogout; ?>">
                        <a href="logout.php">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span class="hd_tb">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Overlay -->
        <label for="menu-toggle-checkbox" id="overlay" class="overlay"></label>
    </div>
</div>
<!--mobile header-->

<div class="left_bar">
    <div class="logo_dash">
        <a href="#" class="intro-x d-flex gap-2 align-items-center ps-3">
            <img alt="" class="w-6" src="images/logo.png">
            <span class="d-none d-lg-block text-white text-lg ml-3">Students Portal</span>
        </a>
    </div>
    <div class="navigation_sec mt-4">
        <ul>
            <li class="<?php echo $liDashboard; ?>">
                <a href="dashboard.php">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="hd_tb">Dashboard</span>
                </a>
            </li>
            <li class="<?php echo $liStudents; ?>">
                <a href="manage_students.php">
                    <i class="fa-solid fa-users"></i>
                    <span class="hd_tb">Student List</span>
                </a>
            </li>
            <li class="<?php echo $liLogout; ?>">
                <a href="logout.php">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="hd_tb">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>