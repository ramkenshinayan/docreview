<?php
session_start();
if (isset($_SESSION["user"])) {
    if ($_SESSION["role"] == "Admin") {
        // echo 'Login Successful';
    } else {
        echo '<script>
            alert("Not allowed.");
            window.location.href="index.php";
            </script>';
        include("logout.php");
    }
} else {
    echo '<script>
    window.location.href="index.php";
    </script>';
}

require("db.php");
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Number of Users
$totalUsersResult = $conn->query("SELECT * FROM users");
$totalUsers = $totalUsersResult->num_rows;

$onlineUsersResult = $conn->query("SELECT * FROM users WHERE status='Online'");
$onlineUsers = $onlineUsersResult->num_rows;

$offlineUsersResult = $conn->query("SELECT * FROM users WHERE status='Offline'");
$offlineUsers = $offlineUsersResult->num_rows;

// Add User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = generateRandomPassword();
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $status = "Offline";

    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        echo "Error: User with this email already exists.";
    } else {
        $sql = "INSERT INTO users (email, password, firstName, lastName, role, status) 
                    VALUES ('$email', '$password', '$firstName', '$lastName', '$role', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "New user added successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

function generateRandomPassword() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $password = '';
    for ($i = 0; $i < 4; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

// Update User
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $originalEmail = mysqli_real_escape_string($conn, $_POST['original_email']);
    $updatedEmail = mysqli_real_escape_string($conn, $_POST['updated_email']);
    $updatedFirstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $updatedLastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $updatedRole = mysqli_real_escape_string($conn, $_POST['role']);

    $sql = "UPDATE users SET email='$updatedEmail', firstName='$updatedFirstName', lastName='$updatedLastName', role='$updatedRole' WHERE email='$originalEmail'";

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully!";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

// Delete User
if (isset($_POST['delete_user'])) {
    $email = mysqli_real_escape_string($conn, $_POST['delete_user']);

    $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmailQuery);

    if ($result->num_rows > 0) {
        $sql = "DELETE FROM users WHERE email='$email'";

        if ($conn->query($sql) === TRUE) {
            echo "User deleted successfully!";
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    } else {
        echo "Error: User with this email does not exist.";
    }
}
$conn->close();
