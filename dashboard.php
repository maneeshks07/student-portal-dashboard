<?php
include 'includes/connection.php';
include 'includes/chk_login.php';
include 'includes/student.class.php';

$objstd = new Student($conn);

$totalStudents = $objstd->getCount("SELECT COUNT(*) AS total FROM users");
$totalMale     = $objstd->getCount("SELECT COUNT(*) AS total FROM users WHERE gender = 'Male'");
$totalFemale   = $objstd->getCount("SELECT COUNT(*) AS total FROM users WHERE gender = 'Female'");
$newThisMonth  = $objstd->getCount("SELECT COUNT(*) AS total FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())");

$pageTitle          = "Dashboard_Login";
$activePage         = "dashboard";
$breadcrumbLabel    = "Student Dashboard";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include 'head-script.php'; ?>
</head>

<body class="page-dashboard">
    <div class="bg_dashboard h-screen">
        <div class="row">

            <!--left menu-->
            <?php include 'sidebar.php'; ?>
            <!--left menu-->

            <div class="right_bar">

                <!--header-->
                <?php include 'header.php'; ?>
                <!--header-->

                <div class="row mt-4 max_565">

                    <div class="col-6 col-md-6 col-lg-3">
                        <a href="manage_students.php"
                            class="col-span-12  text-white sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-3 box-students">
                                    <div class="d-flex">
                                        <i class="fa-solid fa-user-graduate"></i>
                                    </div>
                                    <div class="fs-2 font-medium leading-8 mt-3"><?php echo $totalStudents; ?></div>
                                    <div class="fs-6  mt-1">Total Students</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3">
                        <a href="manage_students.php"
                            class="col-span-12  text-white sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-3 box-male">
                                    <div class="d-flex">
                                        <i class="fa-solid fa-mars"></i>
                                    </div>
                                    <div class="fs-2 font-medium leading-8 mt-3"><?php echo $totalMale; ?></div>
                                    <div class="fs-6  mt-1">Male Students</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3">
                        <a href="manage_students.php"
                            class="col-span-12  text-white sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-3 box-female">
                                    <div class="d-flex">
                                        <i class="fa-solid fa-venus"></i>
                                    </div>
                                    <div class="fs-2 font-medium leading-8 mt-3"><?php echo $totalFemale; ?></div>
                                    <div class="fs-6  mt-1">Female Students</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-6 col-md-6 col-lg-3">
                        <a href="manage_students.php"
                            class="col-span-12  text-white sm:col-span-6 xl:col-span-3 intro-y">
                            <div class="report-box zoom-in">
                                <div class="box p-3 box-new">
                                    <div class="d-flex">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </div>
                                    <div class="fs-2 font-medium leading-8 mt-3"><?php echo $newThisMonth; ?></div>
                                    <div class="fs-6  mt-1">New This Month</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>

                <div class="row mt-4 max_565 content_row_flex">
                    <div class="col-12 content_col_full">
                        <div class="manage-card">
                            <h2>Students by Gender</h2>
                            <?php
                            $chartRows = array();
                            $chartRes  = mysqli_query($conn, "SELECT gender AS label, COUNT(*) AS total FROM users GROUP BY gender");
                            while ($chartRow = mysqli_fetch_assoc($chartRes)) 
                                {
                                    $chartRows[] = $chartRow;
                                }
                            $palette  = array("#7c3aed", "#0ea5e9", "#f59e0b", "#16a34a", "#ef4444", "#14b8a6");
                            $chartSum = 0;
                            foreach ($chartRows as $chartRow) 
                                {
                                    $chartSum = $chartSum + $chartRow["total"];
                                }
                            if ($chartSum == 0) 
                                {
                                    $chartSum = 1;
                                }
                            $stops  = "";
                            $legend = "";
                            $start  = 0;
                            $n      = 0;
                            foreach ($chartRows as $chartRow) 
                                {
                                    $end    = $start + ($chartRow["total"] / $chartSum) * 100;
                                    $color  = $palette[$n % 6];
                                    $stops  = $stops . $color . " " . $start . "% " . $end . "%,";
                                    $legend = $legend . '<li><i style="background:' . $color . '"></i>' . htmlspecialchars($chartRow["label"]) . ' (' . $chartRow["total"] . ')</li>';
                                    $start  = $end;
                                    $n++;
                                }
                            $stops = rtrim($stops, ",");
                            if ($stops == "") 
                                {
                                    $stops = "#e5e7eb 0% 100%";
                                }
                            ?>
                            <div class="chart_wrap">
                                <div class="donut" style="background:conic-gradient(<?php echo $stops; ?>)"></div>
                                <ul class="legend"><?php echo $legend; ?></ul>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</body>

</html>
