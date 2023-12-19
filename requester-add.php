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
            <a href="requester-view.php">
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
            <a href="requester-track.php">
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
      <div class="profile-details-alone">
        <img src="assets/school.png" alt="">
        <span class="user_name"><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></span>
        <ion-icon name="radio-button-on-outline" class="profile-icon"></ion-icon>
      </div>
    </div>
    <div class="home-content-add">
      <div class="title">
        <ion-icon name="document-text-outline" class="content-icon"></ion-icon>
        <span class="text">Upload Document</span>
      </div>
      <div class="wrapper">
        <form id="upbox" action="includes/upload.php" method="post" enctype="multipart/form-data" onsubmit="return onSubmitForm()">
          <div class="file-upload">
          <input id="fileInput" class="file-input" type="file" name="file" accept=".doc, .docx, .pdf" hidden>
            <ion-icon name="cloud-upload-outline"></ion-icon>
            <p>Browse File to Upload</p><br>
          </div>
          <button id="subbtn" type="submit" name="upload" style="display: none;">Submit </button> 
        </form>
        <section class="progress-area"></section>
        <section class="uploaded-area"></section>
        <div>
            <br>
        </div>
        <div class="table-container">
          <table class="table">
            <thead>
                <tr>
                    <th></th>
                    <th>Office Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT DISTINCT officeName FROM organization";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($row = $result->fetch_assoc()) {
                        if ($count > 5) {
                            break; 
                        }
                        $officeName = $row["officeName"];
                        $reviewerSql = "SELECT firstName, lastName FROM users WHERE email IN (SELECT email FROM organization WHERE officeName = '$officeName')";
                        $reviewerResult = $conn->query($reviewerSql);
                        $reviewers = [];
                        while ($reviewerRow = $reviewerResult->fetch_assoc()) {
                            $reviewers[] = $reviewerRow["firstName"] . ' ' . $reviewerRow["lastName"];
                        }
                        echo "<tr>";
                        echo "<td>$count</td>";
                        echo "<td>";
                        echo "<select name='officeSelect$count' class='office-select'>";
                        echo "<option value='' selected>Select Office</option>";

                        $officeSql = "SELECT DISTINCT officeName FROM organization";
                        $officeResult = $conn->query($officeSql);

                        while ($officeRow = $officeResult->fetch_assoc()) {
                            $currentOffice = $officeRow["officeName"];
                            echo "<option value='$currentOffice'>$currentOffice</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "</tr>";
                        $count++;
                    }
                } else {
                    echo "<tr><td colspan='3'>No data available</td></tr>";
                }
                ?>
            </tbody>
          </table>
        </div>
        <form id="upbox2" onsubmit="return onSubmitForm()">
          <button id="subbtn2" type="submit" name="upload" disabled>Submit</button>
        </form>
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
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <script src="resources/js/requester-upload.js"></script>
  <script src="resources/js/requester-add.js"></script>
  <script src="resources/js/user-home.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</body>
</html>
