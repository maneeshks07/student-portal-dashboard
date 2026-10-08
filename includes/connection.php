<?php

session_start();

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "student_portal_showcase";

$conn = mysqli_connect("localhost","root","","student_portal_showcase");

if (!$conn) 
{
    die("Connection failed: " . mysqli_connect_error());
}

?>
