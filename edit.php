<?php
include 'includes/connection.php';
include 'includes/chk_login.php';
include 'includes/student.class.php';

$objstd  = new Student($conn);

$id = (int) ($_GET["id"]?? 0);

if ($id <= 0) 
        {
            header("Location: manage_students.php");
            exit();
        }

$error = "";

$genders                = array("Male", "Female", "Other");
$hobbyOptions           = array("Reading", "Sports", "Music", "Travel");
$qualificationOptions   = array("SSLC", "Plus Two", "Diploma", "Degree", "PG");

$student = $objstd->getStudentById($id);

if (!$student) 
        {
            header("Location: manage_students.php");
            exit();
        }

$fullname       = $student["fullname"];
$username       = $student["username"];
$email          = $student["email"];
$phone          = $student["phone"];
$gender         = $student["gender"];
$dob            = $student["dob"];
$age            = $student["age"];
$about          = $student["about"];
$hobbies        = $student["hobbies"] != "" ? explode(", ", $student["hobbies"]) : array();
$qualifications = $student["qualifications"] != "" ? explode(", ", $student["qualifications"]) : array();
$oldPhoto       = $student["photo"];

if (isset($_POST["update"])) 
        {
            $fullname       = trim($_POST["fullname"]);
            $username       = trim($_POST["username"]);
            $email          = trim($_POST["email"]);
            $phone          = trim($_POST["phone"]);
            $gender         = isset($_POST["gender"]) ? trim($_POST["gender"]) : "";
            $dob            = trim($_POST["dob"]);
            $age            = trim($_POST["age"]);
            $about          = trim($_POST["about"]);
            $hobbies        = $_POST["hobbies"] ?? array();
            $qualifications = $_POST["qualifications"] ?? array();
            $password       = trim($_POST["password"]);
            $confirmPassword= trim($_POST["confirm_password"]); 
            $photoFile      = $_FILES["photo"] ?? null;

            $updated = $objstd->updateStudent($id, $fullname, $username, $email, $phone, $gender, $dob, $age, $hobbies, $qualifications, $about, $password, $confirmPassword, $photoFile, $oldPhoto);

            if ($updated) 
                {
                    header("Location: manage_students.php?msg=updated");
                    exit();
                } 
            else 
                {
                    $error = $objstd->errorMessage;
                }
        }

$pageTitle = "Edit Student";
$activePage = "students";
$breadcrumbLabel = "Edit Student";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <?php include 'head-script.php'; ?>
</head>

<body class="page-add-edit-student">
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

                            <h2>Edit Student</h2>
                            <p>Update this student's details</p>

                            <?php if ($error != ""): ?>
                                <div class="alert error"><?php echo htmlspecialchars($error); ?></div>
                            <?php endif; ?>

                            <form method="post" enctype="multipart/form-data">

                                <label>Profile Picture</label>
                                <div class="upload-box">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                    <div>Click to upload or drag and drop</div>
                                    <div class="upload-hint">JPG, PNG, WEBP or GIF (MAX. 2MB)</div>
                                    <input type="file" name="photo" accept="image/*">
                                </div>

                                <?php if ($oldPhoto != ""): ?>
                                    <div class="profile-photo-preview">
                                        <img class="profile-photo" src="uploads/<?php echo htmlspecialchars($oldPhoto); ?>"
                                            alt="">
                                        <span>Current photo</span>
                                    </div>
                                <?php endif; ?>

                                <div class="form-grid">
                                    <div>
                                        <label>Full Name</label>
                                        <input type="text" name="fullname"
                                            value="<?php echo htmlspecialchars($fullname); ?>">
                                    </div>

                                    <div>
                                        <label>Username</label>
                                        <input type="text" name="username"
                                            value="<?php echo htmlspecialchars($username); ?>">
                                    </div>

                                    <div>
                                        <label>Email</label>
                                        <input type="email" name="email"
                                            value="<?php echo htmlspecialchars($email); ?>">
                                    </div>

                                    <div>
                                        <label>Phone Number</label>
                                        <input type="tel" name="phone" maxlength="10"
                                            value="<?php echo htmlspecialchars($phone); ?>">
                                    </div>

                                    <div>
                                        <label>Date of Birth</label>
                                        <input type="date" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
                                    </div>

                                    <div>
                                        <label>Age</label>
                                        <select name="age">
                                            <option value="">Select Age</option>
                                            <?php for ($i = 5; $i <= 35; $i++): ?>
                                                <option value="<?php echo $i; ?>" <?php echo $age == $i ? "selected" : ""; ?>>
                                                    <?php echo $i; ?>
                                                </option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>

                                    <div>
                                        <label>Gender</label>
                                        <?php foreach ($genders as $item): ?>
                                            <label class="radio">
                                                <input type="radio" name="gender" value="<?php echo $item; ?>" <?php echo $gender == $item ? "checked" : ""; ?>>
                                                <?php echo $item; ?>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                    <div>
                                        <label>Qualification</label>
                                        <select name="qualifications[]" multiple size="5">
                                            <?php foreach ($qualificationOptions as $item): ?>
                                                <option value="<?php echo $item; ?>" <?php echo in_array($item, $qualifications) ? "selected" : ""; ?>><?php echo $item; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <label>Hobbies</label>
                                <div class="field-group-mb">
                                    <?php foreach ($hobbyOptions as $item): ?>
                                        <label class="checkbox">
                                            <input type="checkbox" name="hobbies[]" value="<?php echo $item; ?>" <?php echo in_array($item, $hobbies) ? "checked" : ""; ?>>
                                            <?php echo $item; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>

                                <label>About</label>
                                <textarea name="about" rows="3"><?php echo htmlspecialchars($about); ?></textarea>

                                <div class="form-grid">
                                    <div>
                                        <label>New Password</label>
                                        <input type="password" name="password"
                                            placeholder="Leave blank to keep current">
                                    </div>

                                    <div>
                                        <label>Confirm Password</label>
                                        <input type="password" name="confirm_password" placeholder="Re-enter password">
                                    </div>
                                </div>

                                <button type="submit" name="update" class="button">Save Changes</button>
                                <a href="manage_students.php" class="button button-muted">Cancel</a>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
