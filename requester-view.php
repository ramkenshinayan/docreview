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
    <!-- MAIN CSS-->
    <link href="resources\css\user-home.css" rel="stylesheet">
    <link href="resources\css\requester-view.css" rel="stylesheet">
</head>
<body>
    <!-- SIDE BAR -->
    <nav class="sidebar close">
        <!-- SIDE BAR HEADER -->
        <header>
            <div class="image-text"><span class="image"><img src="assets/slu_logo.png" alt="logo"></span>
                <div class="text header-text"><span class="name">Saint Louis University</span><span class="task">Document Review Tracker</span></div>
            </div>
            <ion-icon class="toggle" name="chevron-forward-outline"></ion-icon>
        </header>
        <!-- MENU -->
        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-links">
                    <!-- HOME LINK -->
                    <li class="nav-link">
                        <a href="requester-home.html">
                            <ion-icon name="home-outline"></ion-icon><span class="text nav-text">Home</span></a>
                    </li>
                    <!-- TRANSACTION LIST LINK -->
                    <li class="nav-link">
                        <a href="requester-view.html">
                            <ion-icon name="document-outline"></ion-icon><span class="text nav-text">View Requests</span></a>
                    </li>
                    <!-- UPLOADING LINK -->
                    <li class="nav-link">
                        <a href="requester-add.html">
                            <ion-icon name="document-attach-outline"></ion-icon><span class="text nav-text">Add Requests</span></a>
                    </li>
                    <!-- TRACKING LINK -->
                    <li class="nav-link">
                        <a href="requester-track.html">
                            <ion-icon name="document-text-outline"></ion-icon><span class="text nav-text">Track Requests</span></a>
                    </li>
                </ul>
            </div>
            <div class="bottom-content">
                <!-- LOGOUT -->
                <li>
                    <a href="#">
                        <ion-icon name="log-out-outline"></ion-icon><span class="text nav-text">Logout</span></a>
                </li>
                <!-- TOGGLE MODES -->
                <li class="mode">
                    <div class="moon-sun">
                        <ion-icon class="moon" name="moon-outline"></ion-icon>
                        <ion-icon class="sun" name="sunny-outline"></ion-icon>
                    </div><span class="mode-text text">Dark Mode</span>
                    <div class="toggle-switch"><span class="switch"></span></div>
                </li>
            </div>
        </div>
    </nav>
    <section class="home">
        <!-- HEADER -->
        <div class="top">
            <div class="search-box">
                <ion-icon class="search-icon" name="search-outline"></ion-icon><input type="search" placeholder="Search..."></div>
                <div class="profile-details"><img src="assets/school.png" alt=""><span class="user_name">Juan Dela Cruz</span>
                <ion-icon class="profile-icon" name="radio-button-on-outline"></ion-icon>
            </div>
        </div>
        <div class="home-content">
            <div class="overview">
                <div class="title">
                    <ion-icon class="content-icon" name="bar-chart-outline"></ion-icon><span class="text">Request History</span>
                <!-- filter-->
                <div class="filter-box">
                    <div class="filter-btn">Filter<span class="icon"><ion-icon name="chevron-down-outline"></ion-icon></span></div>
                    <ul class="filter-select">
                        <li class="filter-items">Approved</li>
                        <li class="filter-items">Pending</li>
                        <li class="filter-items">Disapproved</li>
                    </ul>
                </div>
                <!-- sort-->
                <div class="sort-box">
                    <div class="sort-btn">Sort<span class="icon"><ion-icon name="chevron-down-outline"></ion-icon></span></div>
                    <ul class="sort-select">
                        <li class="sort-items">Name (A-Z)</li>
                        <li class="sort-items">Name (Z-A)</li>
                        <li class="sort-items">Date (ASC)</li>
                        <li class="sort-items">Date (DESC)</li>
                    </ul>
                </div>
            </div>        
            </div>
                
            <!--transactions-->
            <div class="history">
                <!-- <p>No reviews available</p> -->
                
                <?php
                try {
                    $sql = "SELECT reviewtransaction.*, document.fileName AS DocumentName, document.uploadDate as UploadDate FROM reviewtransaction JOIN document ON reviewtransaction.documentId = document.documentId";

                    // Filtering
                    if (isset($_GET['filter'])) {
                        $filterValue = $_GET['filter']; // Sanitize as needed
                        $sql .= " WHERE status = '$filterValue'";
                    }

                    // Sorting
                    if (isset($_GET['sort'])) {
                        $sortValue = $_GET['sort']; // Sanitize as needed
                        switch ($sortValue) {
                            case 'Name (A-Z)':
                                $sql .= " ORDER BY document.fileName ASC";
                                break;
                            case 'Name (Z-A)':
                                $sql .= " ORDER BY document.fileName DESC";
                                break;
                            case 'Date (ASC)':
                                $sql .= " ORDER BY document.UploadDate ASC";
                                break;
                            case 'Date (DESC)':
                                $sql .= " ORDER BY document.UploadDate DESC";
                                break;
                        }
                    }

                    // Searching
                    if (isset($_GET['search'])) {
                        $searchTerm = $_GET['search']; // Sanitize as needed
                        // Assuming 'fileName' is the column you want to search
                        $sql .= " WHERE document.fileName LIKE '%$searchTerm%'";
                    }

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // Output HTML directly for each row
                            echo '<div class="box" id="box-' . $row['reviewId'] . '">';
                            echo '<div class="content">';
                            echo '<h1 class="name">' . $row['DocumentName'] . '</h1>';
                            echo '<p>Upload Date: <span>' . date('F j, Y', strtotime($row['UploadDate'])) . '</span></p>';
                            if($row['approvedDate'] != 'null'){
                            echo '<p>Approved Date: <span>' .  date('F j, Y', strtotime($row['approvedDate'])) . '</span></p></div>';
                            } else {
                                echo '<p>Approved Date: Not yet Approved</p></div>';
                            }
                            echo '<h3 class="status">' . $row['status'] . '</h3>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No reviews available</p>';
                    }
                } catch (Exception $e) {
                    header('HTTP/1.1 500 Internal Server Error');
                    echo '<p>Error: ' . $e->getMessage() . '</p>';
                }
                ?>

              
            </div>
        </div>
    </section>
    <!-- CUSTOM JS-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule="" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <script src="resources/js/user-home.js"></script>
    <script src="resources\js\requester-view.js"></script>
</body>
</html>
