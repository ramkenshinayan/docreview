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
  <link href="resources\css\requester-track.css" rel="stylesheet">
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
      <div class="search-box">
        <ion-icon class="search-icon" name="search-outline"></ion-icon><input type="search" placeholder="Search..."></div>
        <div class="profile-details">
          <img src="assets/school.png" alt="">
          <span class="user_name"><?php echo $_SESSION["fname"] . " " . $_SESSION["lname"]; ?></span>
          <ion-icon name="radio-button-on-outline" class="profile-icon"></ion-icon>
        </div>
    </div>
    </div>
    <div class="home-content">
      <div class="overview">
        <div class="title">
          <ion-icon class="content-icon" name="bar-chart-outline"></ion-icon><span class="text">Track Requests</span>
        </div>
        <!-- PROGRESS BAR-->
        <div class="right-container">
            <div class ="container">
            <div class="steps"><span class="circle">1</span><span class="circle">2</span><span class="circle">3</span><span class="circle">4</span><span class="circle">5</span>
                <div class="progress-bar"><span class="indicator"></span></div>
            </div>
          </div>
            <!-- TRACKING CONTENT-->
            <div class="content">
                <?php
                    $email = $_SESSION["user"];
                    $sql = "SELECT reviewtransaction.documentId, reviewtransaction.*, document.fileName AS DocumentName, 
                    document.uploadDate as UploadDate, document.content 
                    FROM reviewtransaction 
                    JOIN document ON reviewtransaction.documentId = document.documentId;";
                    $result = $conn->query($sql);
 
                    while ($row = $result->fetch_assoc()) {
                      $documentId = $row['documentId'];
                      if ($documentId !== null) {
                        $sql = "SELECT MIN(rt.sequenceOrder) AS minSequenceOrder, rt.status, rt.documentId, org.officeName, d.content 
                        FROM reviewtransaction rt 
                        JOIN organization org ON rt.email = org.email
                        JOIN document d ON rt.documentId = d.documentId 
                        WHERE (rt.status = 'Pending' OR rt.status = 'Disapproved') AND d.email = '$email'
                        GROUP BY rt.documentId";
                        $result = $conn->query($sql);
  
                        $data = array();
                        if ($result && $result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $data[] = array(
                                    'documentId' => $row['documentId'],
                                    'minOrder' => $row['minSequenceOrder'],
                                    'status' => $row['status'],
                                    'officeName' => $row['officeName'],
                                    'pdfContent' => base64_encode($row['content'])
                                );
                            }
                        }
                        
                        $jsonData = json_encode($data);

                        echo '<script>';
                        echo ' const data = ' . $jsonData . ';';
                        echo '</script>';
                      } 
                    }  
                    
                    if (isset($_POST["upload"])) {
                      if (!empty($_FILES["file"]["name"])) {
                          $fileName = basename($_FILES["file"]["name"]);
                          $allowTypes = array('pdf', 'doc', 'docx');
                          if (in_array($fileType, $allowTypes)) {
                              
                              $version = 1; 
                              $checkVersionQuery = "SELECT MAX(version) as maxVersion FROM document WHERE documentId = '$documentId'";
                              $result = $conn->query($checkVersionQuery);
                  
                              $tmpName = $_FILES["file"]["tmp_name"];
                              $fp = fopen($tmpName, "r");
                              $content = fread($fp, 16777215);
                              $content = addslashes($content);
                              fclose($fp);
                  
                              $update = $conn->query("UPDATE document SET version = '$result+1', content =  '$content' WHERE documentId = '$documentId';");
                              $update = $conn->query("UPDATE reviewtransaction AS rt ON rt.documentId JOIN document 
                              AS d ON rt.documentId = d.documentId SET rt.status = 'Pending' WHERE d.documentId = '$documentId';");

                              if ($update) {
                                  $statusMsg = $fileName . " has been uploaded successfully.";
                              } else {
                                  $statusMsg = "File upload failed, try again.";
                              }
                          } else {
                              $statusMsg = 'Only PDF, DOC, & DOCX files are allowed.';
                          }
                      } else {
                          $statusMsg = 'No file selected.';
                      }
                    }
                  ?>
                  <div id="upload-container" class="wrapper">
                    <h3 class="status">You have not selected a document.</h3>
                  </div>
            </div>
        </div>
        <!-- DOCUMENT LIST -->
        <div class="left-container">
        <form id="approvals" method="POST" action="requester-track.php">
        <?php
          $email = $_SESSION["user"];
          $sql = "SELECT reviewtransaction.documentId, MIN(reviewtransaction.sequenceOrder) AS minSequenceOrder, 
          MIN(reviewtransaction.status) AS status, MIN(document.fileName) AS DocumentName, 
          MIN(document.uploadDate) as UploadDate
          FROM reviewtransaction 
          JOIN document ON reviewtransaction.documentId = document.documentId WHERE document.email = '$email'";
  
          $result = $conn->query($sql);
          $counter = 1;
          
          while ($row = $result->fetch_assoc()) {
              $documentId = $row['documentId'];
              echo '<input type="radio" id="radioButton' . $counter . '" name="radioGroup">';
              echo '<label for="radioButton' . $counter . '">' . $row['DocumentName'] . '<br>' . $row['documentId'] . '</label>';
              $counter++;
          }
        ?>
        </form>
        </div>
    </div>
    <?php
       include("footer.html");
    ?>
  </section>
  <!-- CUSTOM JS -->
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <script src="resources\js\user-home.js"></script>
  <script src="resources\js\requester-track.js"></script>
  </body>
</html>
