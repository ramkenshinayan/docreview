<?php
session_start();
if (isset($_SESSION["user"])) {
  // Identify user role then redirect to corresponding page
  if ($_SESSION["role"] == "Admin") {
    header("Location: admin-home.php");
  }
  if ($_SESSION["role"] == "Requester") {
    header("Location: requester-home.php");
  }
  if ($_SESSION["role"] == "Reviewer") {
    header("Location: reviewer-home.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>SLU Document Review Tracker</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="SLU Document Review Tracker">

  <link rel="icon" type="image/png" href="assets/slu_logo.png">

  <!-- MAIN CSS -->
  <link href="resources/css/styles.css" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
  <?php
  include("header.html");
  ?>
  <div class="wrapper">
    <span class="icon-close"><ion-icon name="close"></ion-icon></span>
    <div class="form-box login">
      <h2>Login</h2>
      <form action="includes/login.php" method="post">
        <div class="input-box">
          <span class="icon"><ion-icon name="mail"></ion-icon></span>
          <input type="email" name="email" required>
          <label>Email</label>
        </div>
        <div class="input-box">
          <span class="icon"><ion-icon name="lock-closed"></ion-icon></span>
          <input type="password" name="password" required>
          <label>Password</label>
        </div>
        <button type="submit" class="btn" name="login">Login</button>
      </form>
    </div>
  </div>
  <!-- HERO SECTION -->
  <section class="hero" id="home">
    <h2>Get your Documents<br>Reviewed for Approval!</h2>
    <p>Document review approval services for<br>Saint Louis University.</p>
    <a href="#request" class="ctas-button">GET STARTED</a>
  </section>
  <!-- REQUEST SECTION -->
  <section class="request" id="request">
    <h1>Request</h1>
    <div class="row">
      <!-- COLUMN ONE -->
      <div class="column">
        <div class="card">
          <div class="icon-req">
            <ion-icon name="document-attach-outline"></ion-icon>
          </div>
          <h3>Upload Document</h3>
          <p>The requester is required to upload a document, 
             wherein they will provide a file or document as part of the submission or request 
             process.
            <br>
            <br> 
            <br> 
          </p>
        </div>
      </div>  
      <!-- COLUMN TWO --> 
      <div class="column">
        <div class="card">
          <div class="icon-req">
            <ion-icon name="add-circle-outline"></ion-icon>
          </div>
          <h3>Select Department</h3>
          <p>The requester is required to choose specific department that will undertake 
             the comprehensive review and evaluation of the document they have previously uploaded.
            <br>
            <br>
          </p>
        </div>
      </div>
      <!-- COLUMN THREE -->
      <div class="column">
        <div class="card">
          <div class="icon-req">
            <ion-icon name="checkmark-done-circle-outline"></ion-icon>
          </div>
          <h3>Approval Review</h3>
          <p>The requester is required to patiently wait for the approval of the document from the designated reviewers they have selected. During this waiting period, 
            the requester can track the progress of the document they have uploaded.
          </p>
        </div>
      </div>
    </div>
  </section>
  
  <?php
  include("footer.html");
  ?>
  <!-- CUSTOM JS -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="resources/js/script.js"></script>
</body>

</html>
