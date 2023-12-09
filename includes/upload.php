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
// function insertTransaction() {
//     global $conn;
//     global $email;
//     global $date;
//                 $last_id = $conn->insert_id;
//                 $process = $conn->query("INSERT INTO transaction (
//                     document_id, 
//                     email, 
//                     comment_id, 
//                     status, 
//                     uploaded_date, 
//                     approved_date) 
//                 VALUES (
//                     '$last_id', 
//                     '$email',
//                     '0',
//                     'pending',
//                     '$date',
//                     'n/a'
//                     )");
// }

echo "<script>
        alert('$statusMsg');
        window.location.href='../requester-home.php';
        </script>";
?>