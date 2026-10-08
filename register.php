<?php
include 'includes/connection.php';
include 'includes/student.class.php';

$objstd  = new Student($conn);

$fullname       = "";
$username       = "";
$email          = "";
$gender         = "";
$dob            = "";
$phone          = "";
$age            = "";
$about          = "";
$hobbies        = [];
$qualifications = [];
$errorMsg       = "";

if (isset($_POST["submit"])) 
    {
        $fullname   = trim($_POST["fullname"]);
        $username   = trim($_POST["username"]);
        $email      = trim($_POST["email"]);
        $password   = trim($_POST["password"]);
        $gender     = "";
        $gender = trim($_POST["gender"] ?? "");
        $phone          = trim($_POST["phone"]);
        $dob            = trim($_POST["dob"]);
        $age            = trim($_POST["age"]);
        $hobbies        = $_POST["hobbies"] ?? [];
        $qualifications = $_POST["qualifications"] ?? [];
        $about          = trim($_POST["about"]);

        if ($fullname == "" || $username == "" || $email == "" || $password == "") 
            {
                $errorMsg = "Please fill in all required fields.";
            } 
        elseif (strlen($password) < 6) 
            {
                $errorMsg = "Password must be at least 6 characters.";
            }
        elseif ($gender == "") 
            {
                $errorMsg = "Please select a gender.";
            } 
        elseif ($phone == "" || !preg_match("/^[0-9]{10}$/", $phone)) 
            {
                $errorMsg = "Please enter a valid 10-digit phone number.";
            } 
        elseif ($dob == "") 
            {
                $errorMsg = "Please enter your date of birth.";
            } 
        elseif ($age == "") 
            {
                $errorMsg = "Please select your age.";
            } 
        elseif (empty($qualifications)) 
            {
                $errorMsg = "Please select a qualification.";
            } 
        elseif ($about == "") 
            {
                $errorMsg = "Please tell us something about yourself.";
            } 
        elseif (!isset($_FILES["photo"]) || $_FILES["photo"]["error"] == UPLOAD_ERR_NO_FILE) 
            {
                $errorMsg = "Please select a photo.";
            } 
        else 
            {
                $registered = $objstd->registerStudent($fullname, $username, $email, $password, $phone, $gender, $dob, $age, $hobbies, $qualifications, $about, $_FILES["photo"]);

                if ($registered) 
                    {
                        header("Location: login.php?registered=1");
                        exit();
                    } 
                else 
                    {
                        $errorMsg = $objstd->errorMessage;
                    }
            }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register - Students Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="form_page_wrap">
        <div class="form_card">

            <h3>Create Account</h3>
            <p>Fill in the details below to register</p>

            <?php if ($errorMsg != ""): ?>
                <div class="form_alert form_alert_error"><?php echo $errorMsg; ?></div>
            <?php endif; ?>

            <form method="post" action="register.php" enctype="multipart/form-data">
                <div class="form_row">
                    <div class="form_grp">
                        <label>Full Name</label>
                        <input type="text" name="fullname" placeholder="Enter full name"
                            value="<?php echo $fullname; ?>">
                    </div>
                    <div class="form_grp">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Enter username"
                            value="<?php echo $username; ?>">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter email" value="<?php echo $email; ?>">
                    </div>
                    <div class="form_grp">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" maxlength="10" placeholder="Enter phone number"
                            value="<?php echo $phone; ?>">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter password">
                    </div>
                    <div class="form_grp">
                        <label>Date of Birth</label>
                        <input type="date" name="dob" value="<?php echo $dob; ?>">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp">
                        <label>Gender</label>
                        <div class="form_radio_group">
                            <label class="form_radio_item">
                                <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") 
                                {
                                    echo "checked";
                                } ?>>
                                Male
                            </label>
                            <label class="form_radio_item">
                                <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") 
                                {
                                    echo "checked";
                                } ?>>
                                Female
                            </label>
                            <label class="form_radio_item">
                                <input type="radio" name="gender" value="Other" <?php if ($gender == "Other") 
                                {
                                    echo "checked";
                                } ?>>
                                Other
                            </label>
                        </div>
                    </div>
                    <div class="form_grp">
                        <label>Age</label>
                        <select name="age">
                            <option value="">Select Age</option>
                            <?php
                            for ($i = 5; $i <= 35; $i++) 
                                {
                                    if ($age == $i) 
                                        {
                                            echo "<option value='$i' selected>$i</option>";
                                        } 
                                    else 
                                        {
                                            echo "<option value='$i'>$i</option>";
                                        }   
                                }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>Hobbies</label>
                        <div class="form_checkbox_group">
                            <label class="form_checkbox_item">
                                <input type="checkbox" name="hobbies[]" value="Reading" <?php if (in_array("Reading", $hobbies)) 
                                {
                                    echo "checked";
                                } ?>>
                                Reading
                            </label>
                            <label class="form_checkbox_item">
                                <input type="checkbox" name="hobbies[]" value="Sports" <?php if (in_array("Sports", $hobbies)) 
                                {
                                    echo "checked";
                                } ?>>
                                Sports
                            </label>
                            <label class="form_checkbox_item">
                                <input type="checkbox" name="hobbies[]" value="Music" <?php if (in_array("Music", $hobbies)) 
                                {
                                    echo "checked";
                                } ?>>
                                Music
                            </label>
                            <label class="form_checkbox_item">
                                <input type="checkbox" name="hobbies[]" value="Travel" <?php if (in_array("Travel", $hobbies)) 
                                {
                                    echo "checked";
                                } ?>>
                                Travel
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>Qualification</label>
                        <select name="qualifications[]" size="5" multiple>
                            <option value="SSLC" <?php if (in_array("SSLC", $qualifications)) 
                            {
                                echo "selected";
                            } ?>>
                                SSLC</option>
                            <option value="Plus Two" <?php if (in_array("Plus Two", $qualifications)) 
                            {
                                echo "selected";
                            } ?>>Plus Two</option>
                            <option value="Diploma" <?php if (in_array("Diploma", $qualifications)) 
                            {
                                echo "selected";
                            } ?>>Diploma</option>
                            <option value="Degree" <?php if (in_array("Degree", $qualifications)) 
                            {
                                echo "selected";
                            } ?>>Degree</option>
                            <option value="PG" <?php if (in_array("PG", $qualifications)) 
                            {
                                echo "selected";
                            } ?>>PG
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>Photo</label>
                        <input type="file" name="photo">
                    </div>
                </div>

                <div class="form_row">
                    <div class="form_grp form_grp_full">
                        <label>About</label>
                        <textarea name="about" rows="3"
                            placeholder="Tell us a little about yourself"><?php echo $about; ?></textarea>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn_update">Register</button>
            </form>

            <p class="form_footer_link">
                Already have an account? <a href="login.php">Login here</a>
            </p>

        </div>
    </div>

</body>

</html>
