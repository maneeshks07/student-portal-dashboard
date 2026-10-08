<?php
include 'includes/connection.php';
include 'includes/chk_login.php';
include 'includes/student.class.php';

$objstd  = new Student($conn);

$username = $_SESSION["username"];
$user     = $objstd->getStudentByUsername($username);

if (!$user) 
    {
        header("Location: logout.php");
        exit();
    }

$id         = (int) $user["id"];
$error      = "";
$message    = "";

if (!empty($_POST["old_password"])) 
    {
        $oldPassword     = trim($_POST["old_password"]);
        $newPassword     = trim($_POST["new_password"]);
        $confirmPassword = trim($_POST["confirm_password"]);

        $changed = $objstd->changePassword($id, $user["password"], $oldPassword, $newPassword, $confirmPassword);

        if ($changed) 
            {
                $message = "Password changed successfully.";
            } 
        else 
            {
                $error = $objstd->errorMessage;
            }
    }

$pageTitle = "Reset Password";
$activePage = "";
$breadcrumbLabel = "Reset Password";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include 'head-script.php'; ?>
</head>

<body>
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
                        <div class="profile-card">

                            <h2>Change Password</h2>
                            <p>Update the password used to sign in to your account</p>

                            <?php if ($message != ""): ?>
                                <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
                            <?php endif; ?>

                            <?php if ($error != ""): ?>
                                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>

                            <form method="post">
                                <label>Old Password</label>
                                <input type="password" name="old_password" placeholder="Old password"
                                    class="field-group-mb">

                                <label>New Password</label>
                                <input type="password" name="new_password" placeholder="New password"
                                    class="field-group-mb">

                                <label>Confirm New Password</label>
                                <input type="password" name="confirm_password" placeholder="Confirm password"
                                    class="field-group-mb">

                                <button type="submit" class="button">Change Password</button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
