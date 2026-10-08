<?php
session_start();
include "includes/connection.php";

unset($_SESSION["username"]);
header("Location: login.php");
exit();
?>
