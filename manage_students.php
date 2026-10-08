<?php
include 'includes/connection.php';
include 'includes/chk_login.php';
include 'includes/student.class.php';

$objstd  = new Student($conn);

$message = "";
$error   = "";

if (($_GET["msg"] ?? "") == "updated") 
    {
        $message = "Student updated successfully.";
    }

if (($_GET["delete"] ?? "") !== "") 
    {
        $id = (int) $_GET["delete"];

        if ($objstd->deleteStudent($id, $_SESSION["username"])) 
            {
                $message = "Student deleted successfully.";
            } 
        else 
            {
                $error = $objstd->errorMessage;
            }
    }

$fullname = trim($_GET['fullname'] ?? "");
$email    = trim($_GET['email'] ?? "");
$phone    = trim($_GET['phone'] ?? "");

$students = $objstd->searchStudents($fullname, $email, $phone);

$pageTitle      = "Manage Students";
$activePage     = "students";
$breadcrumbLabel= "Students List";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include 'head-script.php'; ?>
</head>

<body class="page-manage-students">
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
                        <div class="manage-card">

                            <h2>Manage Students</h2>

                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <form method="get" class="search-form">
                                        <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>"
                                        placeholder="Search by name">

                                        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>"
                                        placeholder="Search by email">

                                        <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>"
                                        placeholder="Search by phone">

                                        <button type="submit" class="button">Search</button>
                                    </form>
                                </div>

                            <?php if ($message != ""): ?>
                                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
                            <?php endif; ?>

                            <?php if ($error != ""): ?>
                                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>

                            <div class="table-scroll">
                                <table>
                                    <tr>
                                        <th>Photo</th>
                                        <th>Full Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Age</th>
                                        <th>Joined</th>
                                        <th>Actions</th>
                                    </tr>

                                    <?php if ($students && mysqli_num_rows($students) > 0): ?>

                                        <?php while ($student = mysqli_fetch_assoc($students)): ?>

                                            <?php
                                            if ($student["photo"] != "") 
                                                {
                                                    $photo = "uploads/" . $student["photo"];
                                                } else 
                                                {
                                                    $photo = "https://ui-avatars.com/api/?background=1e40af&color=fff&name="
                                                        . urlencode($student["fullname"]);
                                                }
                                            ?>

                                            <tr>
                                                <td>
                                                    <img src="<?php echo htmlspecialchars($photo); ?>" class="student-photo"
                                                        alt="">
                                                </td>

                                                <td><?php echo htmlspecialchars($student["fullname"]); ?></td>
                                                <td><?php echo htmlspecialchars($student["username"]); ?></td>
                                                <td><?php echo htmlspecialchars($student["email"]); ?></td>

                                                <td>
                                                    <?php echo $student["phone"] != "" ? htmlspecialchars($student["phone"]) : "-"; ?>
                                                </td>

                                                <td>
                                                    <?php echo $student["gender"] != "" ? htmlspecialchars($student["gender"]) : "-"; ?>
                                                </td>

                                                <td>
                                                    <?php echo $student["age"] != "" ? htmlspecialchars($student["age"]) : "-"; ?>
                                                </td>

                                                <td>
                                                    <?php
                                                    echo $student["created_at"] != ""
                                                        ? date("d M Y", strtotime($student["created_at"]))
                                                        : "-";
                                                    ?>
                                                </td>

                                                <td class="actions">
                                                    <a href="view_student.php?id=<?php echo $student["id"]; ?>"
                                                        class="view" title="View" aria-label="View">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>

                                                    <a href="edit.php?id=<?php echo $student["id"]; ?>" class="edit"
                                                        title="Edit" aria-label="Edit">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>

                                                    <a href="manage_students.php?delete=<?php echo $student["id"]; ?>"
                                                        class="delete" title="Delete" aria-label="Delete"
                                                        onclick="return confirm('Delete this student?');">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                        <?php endwhile; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="9" class="empty">No students found.</td>
                                        </tr>

                                    <?php endif; ?>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
