<?php
include 'includes/connection.php';
include 'includes/chk_login.php';
include 'includes/student.class.php';

$objstd  = new Student($conn);

$id = (int) $_GET["id"];

if ($id <= 0) 
    {
        header("Location: manage_students.php");
        exit();
    }

$student = $objstd->getStudentById($id);

if (!$student) 
    {
        header("Location: manage_students.php");
        exit();
    }

if ($student["photo"] != "") 
    {
        $photo = "uploads/" . $student["photo"];
    } 
else 
    {
        $photo = "https://ui-avatars.com/api/?background=1e40af&color=fff&name=" . urlencode($student["fullname"]);
    }

$hobbies        = $student["hobbies"] != "" ? explode(", ", $student["hobbies"]) : array();
$qualifications = $student["qualifications"] != "" ? explode(", ", $student["qualifications"]) : array();

$pageTitle       = "View Student";
$activePage      = "students";
$breadcrumbLabel = "View Student";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include 'head-script.php'; ?>
</head>

<body class="page-view-student">
    <div class="bg_dashboard h-screen">
        <div class="row">

            <!--left menu-->
            <?php include 'sidebar.php'; ?>
            <!--left menu-->

            <div class="right_bar">

                <!--header-->
                <?php include 'header.php'; ?>
                <!--header-->

                <div class="row mt-4 max_565 content_row_flex">
                    <div class="col-12 content_col_full">

                        <div class="view-back-row">
                            <a href="manage_students.php" class="view-back-btn">
                                <i class="fa-solid fa-arrow-left"></i> Back
                            </a>
                        </div>

                        <!-- Top profile summary card -->
                        <div class="view-card view-summary-card">
                            <div class="view-summary-grid">

                                <div class="view-summary-photo">
                                    <img src="<?php echo htmlspecialchars($photo); ?>" alt="">
                                    <div>
                                        <h3><?php echo htmlspecialchars($student["fullname"]); ?></h3>
                                        <p>ID : <?php echo (int) $student["id"]; ?></p>
                                        <p>Age : <?php echo $student["age"] != "" ? htmlspecialchars($student["age"]) : "-"; ?></p>
                                        <p>Gender : <?php echo $student["gender"] != "" ? htmlspecialchars($student["gender"]) : "-"; ?></p>
                                        <span class="view-status-badge">Registered Student</span>
                                    </div>
                                </div>

                                <div class="view-summary-block">
                                    <h4>Contact Details</h4>
                                    <p><i class="fa-solid fa-envelope"></i> <?php echo htmlspecialchars($student["email"]); ?></p>
                                    <p><i class="fa-solid fa-phone"></i>
                                        <?php echo $student["phone"] != "" ? htmlspecialchars($student["phone"]) : "-"; ?>
                                    </p>
                                </div>

                                <div class="view-summary-block">
                                    <h4>Details</h4>
                                    <p>Username <i class="fa-solid fa-arrow-right"></i> <?php echo htmlspecialchars($student["username"]); ?></p>
                                    <p>DOB <i class="fa-solid fa-arrow-right"></i>
                                        <?php echo $student["dob"] != "" ? date("d M Y", strtotime($student["dob"])) : "-"; ?>
                                    </p>
                                    <p>Joined <i class="fa-solid fa-arrow-right"></i>
                                        <?php echo $student["created_at"] != "" ? date("d M Y, h:i A", strtotime($student["created_at"])) : "-"; ?>
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- Qualifications / Hobbies -->
                        <div class="view-two-col">

                            <div class="view-card">
                                <h4 class="view-card-title">Qualifications</h4>

                                <?php if (count($qualifications) > 0): ?>
                                    <?php foreach ($qualifications as $i => $item): ?>
                                        <div class="view-row">
                                            <span class="view-row-label">Qualification <?php echo $i + 1; ?></span>
                                            <span class="view-row-value">: <?php echo htmlspecialchars($item); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="view-row">
                                        <span class="view-row-value">No qualifications added.</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="view-card">
                                <h4 class="view-card-title">Hobbies</h4>

                                <?php if (count($hobbies) > 0): ?>
                                    <?php foreach ($hobbies as $i => $item): ?>
                                        <div class="view-row">
                                            <span class="view-row-label">Hobby <?php echo $i + 1; ?></span>
                                            <span class="view-row-value">: <?php echo htmlspecialchars($item); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="view-row">
                                        <span class="view-row-value">No hobbies added.</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>

                        <!-- About -->
                        <div class="view-card">
                            <h4 class="view-card-title">About</h4>
                            <p class="view-about-text">
                                <?php echo $student["about"] != "" ? nl2br(htmlspecialchars($student["about"])) : "No details added."; ?>
                            </p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
