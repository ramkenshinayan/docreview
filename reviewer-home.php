<?php
session_start();
require("includes/db.php");
include("includes/reviewer.php");
$result = $conn->query("SELECT * FROM transaction");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="SLU Document Review Tracker">
  <title>SLU Document Review Tracker</title>

  <link rel="icon" type="image/png" href="assets/slu_logo.png">
  <!-- MAIN CSS -->
  <link href="resources/css/user-home.css" rel="stylesheet">
</head>

<body>
  <nav class="sidebar close">
    <header>
      <div class="image-text">
        <span class="image">
          <img src="assets/slu_logo.png" alt="logo">
        </span>
        <div class="text header-text">
          <span class="name">Saint Louis University</span>
          <span class="task">Document Review Tracker</span>
        </div>
      </div>
      <ion-icon name="chevron-forward-outline" class="toggle"></ion-icon>
    </header>
    <div class="menu-bar">
      <div class="menu">
        <ul class="menu-links">
          <li class="nav-link">
            <a href="#">
              <ion-icon name="file-tray-stacked-outline"></ion-icon>
              <span class="text nav-text">View Approvals</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#">
              <ion-icon name="checkbox-outline"></ion-icon>
              <span class="text nav-text">For Approval</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#">
              <ion-icon name="file-tray-full-outline"></ion-icon>
              <span class="text nav-text">Track Approvals</span>
            </a>
          </li>
        </ul>
      </div>
      <div class="bottom-content">
        <li class="">
          <a href="includes/logout.php">
            <ion-icon name="log-out-outline"></ion-icon>
            <span class="text nav-text">Logout</span>
          </a>
        </li>
        <li class="mode">
          <div class="moon-sun">
            <ion-icon name="moon-outline" class="moon"></ion-icon>
            <ion-icon name="sunny-outline" class="sun"></ion-icon>
          </div>
          <span class="mode-text text">Dark Mode</span>
          <div class="toggle-switch">
            <span class="switch"></span>
          </div>
        </li>
      </div>
    </div>
  </nav>

  <section class="home">
    <div class="top">
      <div class="profile-details">
        <img src="assets/school.png" alt="">
        <span class="user_name"><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></span>
        <ion-icon name="radio-button-on-outline" class="profile-icon"></ion-icon>
      </div>
    </div>

    <div class="home-content">
      <div class="overview">
        <div class="title">
          <ion-icon name="bar-chart-outline" class="content-icon"></ion-icon>
          <span class="text">Approvals</span>
        </div>
        <div>
          <table style="width:600px; line-height:40px;">
            <tr>
            </tr>
            <th> Document ID </th>
            <th> Email </th>
            <th> Status </th>
            <th> Uploaded Date </th>
            <th> Approved Date </th>
            </tr>
            <?php
            $rows = $result->fetch_assoc();
            for ($i = 0; $i < $result->num_rows; $i++) {
            ?>
              <tr>
                <td><?php echo $rows['document_id']; ?></td>
                <td><?php echo $rows['email']; ?></td>
                <td><?php echo $rows['status']; ?></td>
                <td><?php echo $rows['uploaded_date']; ?></td>
                <td><?php echo $rows['approved_date']; ?></td>
              </tr>
            <?php
            }
            ?>
        </div>
      </div>
  </section>


  <!-- CUSTOM JS -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="resources/js/requester-home.js"></script>
</body>

</html>