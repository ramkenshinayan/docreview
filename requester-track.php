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
        <ion-icon class="search-icon" name="search-outline"></ion-icon><input type="search", id="searchInput", placeholder="Search...", onkeydown="handleSearch(event)"></div>
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
                
                    $data = array(); 

                    while ($row = $result->fetch_assoc()) {
                      $documentId = $row['documentId'];
                      if ($documentId !== null) {
                        $innerSql  = "SELECT MIN(rt.sequenceOrder) AS minSequenceOrder, rt.status, rt.documentId, org.officeName, d.content
                              FROM reviewtransaction rt JOIN organization org ON rt.email = org.email JOIN document d ON rt.documentId = d.documentId
                              JOIN ( SELECT documentId, MAX(version) AS maxVersion FROM document GROUP BY documentId) maxVersions 
                              ON d.documentId = maxVersions.documentId AND d.version = maxVersions.maxVersion
                              WHERE (rt.status = 'Ongoing' OR rt.status = 'Disapproved') AND d.email = '$email'
                              GROUP BY rt.documentId ";
                              
                        $innerResult = $conn->query($innerSql); 
  
                        if ($innerResult  && $innerResult ->num_rows > 0) {
                            while ($innerRow  = $innerResult ->fetch_assoc()) {
                                $data[] = array(
                                    'documentId' => $innerRow ['documentId'],
                                    'minOrder' => $innerRow ['minSequenceOrder'],
                                    'status' => $innerRow ['status'],
                                    'officeName' => $innerRow ['officeName'],
                                    'pdfContent' => base64_encode($innerRow ['content'])
                                );
                            }
                        }
                        
        
                      } 
                    }  

                    $jsonData = json_encode($data);

                    echo '<script>';
                    echo ' const data = ' . $jsonData . ';';
                    echo '</script>';

                    if (isset($_POST["upload"])) {
                      if (!empty($_FILES["file"]["name"])) {
                          $fileName = basename($_FILES["file"]["name"]);
                          $targetFilePath = "upload/" . $fileName;
                          $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
                          $allowTypes = array('pdf', 'doc', 'docx');
                          if (in_array($fileType, $allowTypes)) {
                              $date = date("Y-m-d");
                              $checkVersionQuery = "SELECT MAX(version)+1 as maxVersion FROM document WHERE documentId = '$documentId'";
                              $versionResult = $conn->query($checkVersionQuery);
                             
                  
                              $tmpName = $_FILES["file"]["tmp_name"];
                              $fp = fopen($tmpName, "r");
                              $content = fread($fp, 16777215);
                              $content = addslashes($content);
                              fclose($fp);

                              if ($versionResult) {
                                $versionData = $versionResult->fetch_assoc();
                                $version = (string) $versionData['maxVersion'];
                                $insert = $conn->query("INSERT INTO document (documentId, email, fileName, version, fileType, uploadDate, content) 
                                                          VALUES ('$documentId', '$email', '$fileName', '$version', '$fileType', '$date' ,'$content')");
                                $update = $conn->query("UPDATE reviewtransaction AS rt 
                                                              JOIN document AS d ON rt.documentId = d.documentId 
                                                              SET rt.status = 'Ongoing' 
                                                              WHERE d.documentId = '$documentId' AND rt.status = 'Disapproved';");
                              }
                  
                              if ($insert && $update) {
                                  $successMessage  = $fileName . " has been uploaded successfully.";
                                  echo "<script>
                                  alert('$successMessage');
                                  window.location.href='requester-track.php';
                                  </script>";
                                  exit; 
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
                      if (!empty($successMessage)) {
                        echo "<script>
                                alert('$successMessage');
                                window.location.reload(); // Reload the page on successful upload
                              </script>";
                        exit; 
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
          $sql = "SELECT document.documentId, document.fileName
          FROM reviewtransaction
          JOIN document ON reviewtransaction.documentId = document.documentId
          WHERE (reviewtransaction.status = 'Ongoing' OR reviewtransaction.status = 'Disapproved')
                AND document.email = '$email'
          GROUP BY document.documentId";

          if (isset($_GET['search'])) {
            $searchTerm = $_GET['search']; 
            $sql .= " AND document.fileName LIKE '%$searchTerm%'";
          } 

          $result = $conn->query($sql);
          $counter = 1;
          
          
          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $documentId = $row['documentId'];
              echo '<input type="radio" id="radioButton' . $counter . '" name="radioGroup">';
              echo '<label for="radioButton' . $counter . '">' . $row['fileName'] . '<br>' . $row['documentId'] . '</label>';
              $counter++;
            }
          } else {
            echo '<p>No results found</p>';
          }
        ?>
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
  <script src="resources\js\user-home.js"></script>
  <script src="resources\js\requester-track.js"></script>
  </body>
</html>
