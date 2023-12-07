<?php
session_start();
include('includes/requester.php');
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="resources/css/user-home.css" rel="stylesheet">
  <link href="resources/css/requester-add.css" rel="stylesheet">
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
            <a href="requester-home.php">
              <ion-icon name="home-outline"></ion-icon>
              <span class="text nav-text">Home</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#">
              <ion-icon name="document-outline"></ion-icon>
              <span class="text nav-text">View Requests</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="requester-add.php">
              <ion-icon name="document-attach-outline"></ion-icon>
              <span class="text nav-text">Add Requests</span>
            </a>
          </li>
          <li class="nav-link">
            <a href="#">
              <ion-icon name="document-text-outline"></ion-icon>
              <span class="text nav-text">Track Requests</span>
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
      <div class="wrapper">
        <form action="includes/upload.php" method="post" enctype="multipart/form-data">
          <input class="file-input" type="file" name="file" accept=".doc, .docx, .pdf" hidden>
          <ion-icon name="cloud-upload-outline"></ion-icon>
          <p>Browse File to Upload</p><br>
          <button type="submit" name="upload">Submit</button>
        </form>
        <section class="progress-area"></section>
        <section class="uploaded-area"></section>
        <div class="circle-container">
          <div class="sequenceTitle">
            <p>Select the Sequence of Review</p>
          </div>
          <div class="seq">
            <div class="circle">1</div>
            <div class="btn-group">
              <button type="button" class="btn btn-secondary dropdown-toggle custom-dropdown-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reviwer
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Unit</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Academic Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Finance</a></li>
                <li><a class="dropdown-item" href="#">Office for Legal Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Administration</a></li>
              </ul>
            </div>
          </div>

          <div class="seq">
            <div class="circle">2</div>
            <div class="btn-group">
              <button type="button" class="btn btn-secondary dropdown-toggle custom-dropdown-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reviwer
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Unit</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Academic Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Finance</a></li>
                <li><a class="dropdown-item" href="#">Office for Legal Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Administration</a></li>
              </ul>
            </div>
          </div>

          <div class="seq">
            <div class="circle">3</div>
            <div class="btn-group">
              <button type="button" class="btn btn-secondary dropdown-toggle custom-dropdown-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reviwer
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Unit</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Academic Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Finance</a></li>
                <li><a class="dropdown-item" href="#">Office for Legal Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Administration</a></li>
              </ul>
            </div>
          </div>

          <div class="seq">
            <div class="circle">4</div>
            <div class="btn-group">
              <button type="button" class="btn btn-secondary dropdown-toggle custom-dropdown-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reviwer
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Unit</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Academic Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Finance</a></li>
                <li><a class="dropdown-item" href="#">Office for Legal Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Administration</a></li>
              </ul>
            </div>
          </div>

          <div class="seq">
            <div class="circle">5</div>
            <div class="btn-group">
              <button type="button" class="btn btn-secondary dropdown-toggle custom-dropdown-btn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Reviwer
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Unit</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Academic Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Finance</a></li>
                <li><a class="dropdown-item" href="#">Office for Legal Affairs</a></li>
                <li><a class="dropdown-item" href="#">Office of the Vice President for Administration</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
  </section>


  <!-- CUSTOM JS -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="resources/js/requester-home.js"></script>
  <script src="resources/js/requester-upload.js"></script>
</body>

</html>