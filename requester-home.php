<?php
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
  <link href="resources\css\user-home.css" rel="stylesheet">
</head>
<body>
  <!-- SIDE BAR -->
  <nav class="sidebar close">
    <!-- SIDE BAR HEADER -->
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
          <!-- HOME LINK -->
          <li class="nav-link">
              <a href="requester-home.php">
                  <ion-icon name="home-outline"></ion-icon><span class="text nav-text">Home</span></a>
          </li>
          <!-- TRANSACTION LIST LINK -->
          <li class="nav-link">
              <a href="requester-view.php">
                  <ion-icon name="document-outline"></ion-icon><span class="text nav-text">View Requests</span></a>
          </li>
          <!-- UPLOADING LINK -->
          <li class="nav-link">
              <a href="requester-add.php">
                  <ion-icon name="document-attach-outline"></ion-icon><span class="text nav-text">Add Requests</span></a>
          </li>
          <!-- TRACKING LINK -->
          <li class="nav-link">
              <a href="requester-track.php">
                  <ion-icon name="document-text-outline"></ion-icon><span class="text nav-text">Track Requests</span></a>
          </li>
      </ul>
      </div>
      <div class="bottom-content">
        <!-- LOG OUT -->
        <li class="">
          <a href="includes/logout.php">
            <ion-icon name="log-out-outline"></ion-icon>
            <span class="text nav-text">Logout</span>
          </a>
        </li>
        <!-- TOGGLE MODES -->
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
    <!-- HEADER -->
    <div class="top">
      <div class="profile-details-alone">
        <img src="assets/school.png" alt="">
        <span class="user_name"><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></span>
        <ion-icon name="radio-button-on-outline" class="profile-icon"></ion-icon>
      </div>
    </div>
    <div class="home-content">
      <div class="overview">
        <div class="title">
          <ion-icon name="bar-chart-outline" class="content-icon"></ion-icon>
          <span class="text">Home</span>
        </div>
        <div class="banner">
          <h3>Welcome Back, <span><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></span> ! <span>
              <a href="requester-add.php" class="get-started-btn">
              <button type="button">Get Started</button>
              </a></span></h3>
          <img src="assets\home-img.png" alt="home">
      </div>
      <?php 
        // Check if the user is logged in and has the appropriate role
        if (isset($_SESSION["user"]) && $_SESSION["role"] == "Requester") {
          // Get the email from the session
          $userEmail = $_SESSION["user"];
        } else {
          echo '<script>
              alert("Not allowed or not logged in.");
              window.location.href="index.php";
          </script>';
          include("logout.php");
        }

        $pendingResult = $conn->query("SELECT COUNT(d.documentId) AS pendingDocumentCount
        FROM document d
        JOIN reviewtransaction rt ON d.documentId = rt.documentId
        WHERE rt.status = 'Ongoing' AND d.email = '$userEmail'"
        );
        $pendingRow = $pendingResult->fetch_assoc();
        $pending = $pendingRow['pendingDocumentCount'];


        $rejectedResult = $conn->query("SELECT COUNT(d.documentId) AS rejectedDocumentCount
        FROM document d
        JOIN reviewtransaction rt ON d.documentId = rt.documentId
        WHERE rt.status = 'Disapproved' AND d.email = '$userEmail'"
        ); 
        $rejectedRow = $rejectedResult->fetch_assoc();
        $rejected = $rejectedRow['rejectedDocumentCount'];

        $approvedResult = $conn->query("SELECT COUNT(d.documentId) AS approvedDocumentCount
        FROM document d
        JOIN reviewtransaction rt ON d.documentId = rt.documentId
        WHERE rt.status = 'Approved' AND d.email = '$userEmail'"
        );
        $approvedRow = $approvedResult->fetch_assoc();
        $approved = $approvedRow['approvedDocumentCount'];

        $totalDocumentsResult = $conn->query("SELECT COUNT(documentId) AS totalDocumentCount FROM document WHERE email = '$userEmail'; ");
        $totalDocumentsRow = $totalDocumentsResult->fetch_assoc();
        $totalDocuments = $totalDocumentsRow['totalDocumentCount'];
        
        $recentUploadsResult = $conn->query("SELECT COUNT(documentId) AS recentUploadsCount FROM document WHERE email = '$userEmail' AND uploadDate BETWEEN CURDATE() - INTERVAL 1 WEEK AND CURDATE();");
        $recentUploadsRow = $recentUploadsResult->fetch_assoc();
        $recentUploads = $recentUploadsRow['recentUploadsCount'];

      ?>
      <div class="boxes">
          <div class="box box1">
            <ion-icon name="file-tray-full-outline" class="box-icon"></ion-icon>
            <span class="text">Pending Approvals</span>
            <span class="number" id="pending-approvals"><?php echo $pending ?></span>
          </div>
          <div class="box box2">
            <ion-icon name="close-outline" class="box-icon"></ion-icon>
            <span class="text">Rejected Approvals</span>
            <span class="number" id="rejected approvals"><?php echo $rejected ?></span>
          </div>
          <div class="box box3">
            <ion-icon name="documents-outline" class="box-icon"></ion-icon>
            <span class="text">Total Approvals</span>
            <span class="number" id="total-approvals"><?php echo $approved ?></span>
          </div>
          <div class="box box4">
            <ion-icon name="documents-outline" class="box-icon"></ion-icon>
            <span class="text">Total Documents</span>
            <span class="number" id="total-documents"><?php echo $totalDocuments ?></span>
          </div>
          <div class="box box5">
            <ion-icon name="documents-outline" class="box-icon"></ion-icon>
            <span class="text">Documents Uploaded in the Past Week</span>
            <span class="number" id="total-documents"><?php echo $recentUploads ?></span>
          </div>
      </div>
      <div class="footer">
      <?php
        include("footer.html");
      ?>
      </div>
  </section>
  <!-- CUSTOM JS -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="resources/js/user-home.js"></script>
</body>
</html>
