<?php
require("db.php");
session_start();

if (isset($_POST["login"])) {
    // Receive login details
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);

    // Verify login details
    $loginDetailsStmt = $conn->prepare("SELECT * FROM users WHERE email=? AND password=?");
    $loginDetailsStmt->bind_param("ss", $email, $password);
    $loginDetailsStmt->execute();
    $loginDetailsResult = $loginDetailsStmt->get_result();
    $loginDetailsRow = $loginDetailsResult->fetch_assoc();

    // Check status
    if ($loginDetailsRow["status"] == "Online") {
        echo '<script>
            alert("User is already logged in.");
            window.location.href="../index.php";
            </script>';
        exit();
    }
} else {
    // Identify user role then redirect to corresponding page
    if ($_SESSION["role"] == "Admin") {
        echo '<script>
            alert("A user is already logged in.");
            window.location.href="../admin-home.php";
            </script>';
    }
    if ($_SESSION["role"] == "Requester") {
        echo '<script>
            alert("A user is already logged in.");
            window.location.href="../requester-home.php";
            </script>';
    }
    if ($_SESSION["role"] == "Reviewer") {
        echo '<script>
            alert("A user is already logged in.");
            window.location.href="../reviewer-home.php";
            </script>';
    }
}

// If a result exists, otherwise prompt invalid
if ($loginDetailsResult->num_rows > 0) {
    // Initialize session with user's details
    $_SESSION["user"] = $email;
    $_SESSION["fname"] = $loginDetailsRow["firstName"];
    $_SESSION["lname"] = $loginDetailsRow["lastName"];
    $_SESSION["role"] = $loginDetailsRow["role"];

    // Make user online
    $updateStatusStmt = "UPDATE users SET status='Online' WHERE email='$email'";
    mysqli_query($conn, $updateStatusStmt);

    // Identify user role then redirect to corresponding page
    if ($loginDetailsRow["role"] == "Admin") {
        header("Location: ../admin-home.php");
    }
    if ($loginDetailsRow["role"] == "Requester") {
        header("Location: ../requester-home.php");
    }
    if ($loginDetailsRow["role"] == "Reviewer") {
        header("Location: ../reviewer-home.php");
    }
} else {
    echo '<script>
            alert("Incorrect email or password.");
            window.location.href="../index.php";
            </script>';
    exit();
}
