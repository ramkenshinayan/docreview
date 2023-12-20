<?php
session_start();
include("includes/admin.php");
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
  <link href="resources/css/admin-home.css" rel="stylesheet">
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
            <a href="#home">
              <ion-icon name="home-outline"></ion-icon>
              <span class="text nav-text">Home</span>
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
  <section class="home" id="home">
    <div class="top">
      <div class="search-box">
        <ion-icon class="search-icon" name="search-outline"></ion-icon>
        <input type="search", id="searchInput", placeholder="Search...", onkeydown="handleSearch(event)">
      </div>

      <div class="profile-details-alone">
        <img src="assets/school.png" alt="">
        <span class="user_name"><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></span>
        <ion-icon name="radio-button-on-outline" class="profile-icon"></ion-icon>
      </div>
    </div>
    </div>
    <div class="home-content-add">
      <div class="overview">
        <div class="title">
          <ion-icon name="bar-chart-outline" class="content-icon"></ion-icon>
          <span class="text">Home</span>
        </div>
        <div class="boxes">
          <div class="box box1">
            <ion-icon name="people-outline" class="box-icon"></ion-icon>
            <span class="text">Total Users</span>
            <span class="number" id="total-users"><?php echo $totalUsers ?></span>
          </div>
          <div class="box box2">
            <ion-icon name="radio-button-on-outline" class="box-icon"></ion-icon>
            <span class="text">Online Users</span>
            <span class="number" id="online-users"><?php echo $onlineUsers ?></span>
          </div>
          <div class="box box3">
            <ion-icon name="radio-button-off-outline" class="box-icon"></ion-icon>
            <span class="text">Offline Users</span>
            <span class="number" id="offline-users"><?php echo $offlineUsers ?></span>
          </div>
        </div>
        <div class="user-list">
          <div class="title">
            <ion-icon name="people-outline" class="content-icon"></ion-icon>
            <span class="text">List of Users</span>
            <button id="addUserBtn" class="add-user-btn">+</button>
          </div>
          
          <div class="container-table">
            <table>
              <thead>
                <tr>
                  <th>Email</th>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["firstName"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["lastName"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["role"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                echo "<td>";
                echo "<button class='update-user-btn' data-email='" . htmlspecialchars($row["email"]) . "'>Update</button>";
                echo "<button class='delete-user-btn' data-email='" . htmlspecialchars($row["email"]) . "'>Delete</button>";
                echo "</td>";
                echo "</tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
  </section>
  <section class="manage" id="manage">
    <!-- CUSTOM JS -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="resources/js/user-home.js"></script>
    <script src="resources/js/admin-home.js"></script>
</body>
</html>
