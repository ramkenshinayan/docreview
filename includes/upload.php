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
            $insert = $conn->query("INSERT INTO reviewtransaction (reviewId, documentId, email, status, approvedDate) 
                                        VALUES ('$documentId', '$documentId', '$email', 'ongoing', 'Y-m-d')");
            
            for ($i = 1; $i <= 5; $i++) {
                $selectedOffice = $_POST["officeSelect" . $i];
        
           
                $dataSql = "SELECT email FROM organization WHERE officeName = ?";
                    
                $dataStmt = $conn->prepare($dataSql);
                $dataStmt->bind_param("s", $selectedOffice);
                $dataStmt->execute();
                $dataResult = $dataStmt->get_result();
                    
                $dataRow = $dataResult->fetch_assoc();
                $revEmail = $dataRow["email"];
                $sequenceOrder = $i;
                    
                $dataStmt->close();
                    
                $status = ($i == 1) ? 'ongoing' : 'standby';
               
                $insert =  $conn->query("INSERT INTO reviewsequence (reviewId, email, sequenceOrder, Status) 
                                        VALUES ('$documentId', '$revEmail', '$sequenceOrder', '$status')");
                
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
