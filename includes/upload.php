<?php
session_start();
require("db.php");
$statusMsg = '';
$email = $_SESSION["user"];
$date = date("Y-m-d"); 

if (isset($_POST["upload"])) {
    if (!empty($_FILES["file"]["name"])) {
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = "upload/" . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('pdf', 'doc', 'docx');
        if (in_array($fileType, $allowTypes)) {
            $lastDocumentIdQuery = "SELECT MAX(documentId) as maxDocumentId FROM document";
            $result = $conn->query($lastDocumentIdQuery);
            if ($result && $row = $result->fetch_assoc()) {
                $documentId = $row['maxDocumentId'] + 1; 
            } else {
                $documentId = 1; 
            }

            $version = 1; 
            $checkVersionQuery = "SELECT MAX(version) as maxVersion FROM document WHERE email = '$email'";
            $result = $conn->query($checkVersionQuery);

            $tmpName = $_FILES["file"]["tmp_name"];
            $fp = fopen($tmpName, "r");
            $content = fread($fp, 16777215);
            $content = addslashes($content);
            fclose($fp);

            $insert = $conn->query("INSERT INTO document (documentId, email, fileName, version, fileType, uploadDate, content) 
                                        VALUES ('$documentId', '$email', '$fileName', '$version', '$fileType', '$date' ,'$content')");
           
           $totalOffices = isset($_POST['total_offices']) ? $_POST['total_offices'] : 0;

           for ($i = 1; $i <= $totalOffices; $i++) {
                $reviewerEmailKey = 'reviewer_email_' . $i;
        
                $selectedReviewerName = isset($_POST[$reviewerEmailKey]) ? $_POST[$reviewerEmailKey] : '';
            
                $reviewerNameParts = explode(' ', $selectedReviewerName);
                $firstName = $reviewerNameParts[0];
                $lastName = $reviewerNameParts[1];
            
                $reviewerEmailQuery = "SELECT email FROM users WHERE firstName = '$firstName' AND lastName = '$lastName'";
                $reviewerResult = $conn->query($reviewerEmailQuery);
            
                if ($reviewerResult && $row = $reviewerResult->fetch_assoc()) {
                    $revEmail = $row['email'];
                } else {
                    $revEmail = ''; 
                }
                $sequenceOrder = $i;
                $approvedDate = '0000-00-00';                     
                $status = ($i == 1) ? 'Ongoing' : 'Standby'; 
               
                $insert =  $conn->query("INSERT INTO reviewtransaction (documentId, email, sequenceOrder, status, approvedDate) 
                                        VALUES ('$documentId', '$revEmail', '$sequenceOrder', '$status', '$approvedDate')");
                
            }        

            if ($insert) {
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

echo "<script>
        alert('$statusMsg');
        window.location.href='../requester-home.php';
        </script>";
?>