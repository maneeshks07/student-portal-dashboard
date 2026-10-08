<?php
include 'includes/connection.php';
include 'includes/student.class.php';

$objstd   = new Student($conn);
$username = "";
$errorMsg = "";

if (isset($_POST["login"])) 
    {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        if ($username === "" || $password === "") 
            {
                $errorMsg = "Please enter username and password.";
            } 
        else 
            {
            if ($objstd->login($username, $password)) 
                {
                    header("Location: dashboard.php");
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
    <title>Students Portal</title>
    <meta name  ="viewport" content ="width=device-width, initial-scale=1">
    <link rel   ="stylesheet" href  ="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <link rel   ="stylesheet" href  ="css/style.css">
    <link rel="stylesheet" href="css/theme.css">
</head>
<body class="lg1">
<div class="lg1_wrap">
  <div class="lg1_art"><span class="tag">STUDENT PORTAL</span><h1>Learn. Track. Grow.</h1><p>One place for admissions, profiles and results.</p></div>
  <div class="lg1_form"><h2>Welcome back</h2><p class="sub">Sign in to continue</p><?php if ($errorMsg !== ""): ?><div class="alert_msg"><?php echo htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8'); ?></div><?php endif; ?>
   <form method="post" action="login.php"><div class="fld"><i class="fa-solid fa-user"></i><input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"></div><div class="fld"><i class="fa-solid fa-lock"></i><input type="password" name="password" placeholder="Password"></div><button type="submit" name="login" class="comn_btn_2">Sign in</button></form></div>
</div>
</body>
</html>
