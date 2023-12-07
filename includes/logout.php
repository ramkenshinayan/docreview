<?php
require('db.php');
session_start();
$email = $_SESSION["user"];

$updateStatusStmt = "UPDATE users SET status='Offline' WHERE email='$email'";
mysqli_query($conn, $updateStatusStmt);

session_unset();
session_destroy();

header("Location: ../index.php");
